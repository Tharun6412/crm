<?php

namespace APP\Http\Controllers\Billing;

use App\Enums\ConnectionType;
use App\Enums\Constants;
use App\Enums\InvoiceType;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\BillInvoiceConsumption;
use App\Models\Invoice\BillInvoiceConsumptionDetails;
use App\Models\Master\PriceHistory;
use App\Services\InvoiceGeneration;
use App\Services\InvoiceService;
use App\Services\LedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Enums\ConsumerStatus;
use App\Enums\InvoiceStatus;
use App\Enums\MeterChange;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Enums\TaxType;
use App\Notifications\Consumer\GasbillSmsNotification;
use App\Services\DependentInvoiceService;
use App\Services\PaymentService;
use App\Services\SmsService;
use Illuminate\Validation\ValidationException;

class GasInvoiceController extends Controller
{
    /**
     * 
     */
    public function index() 
    {
        return view('billing.billing.list');
    }
    
    /**
     * Create Gas invoice
     * 
     * @param $id consumer_id
     */
    public function create($id = 0)
    {
        // 1. Check consumer is billable
        $consumer = Consumer::where('id', $id)
            ->where('status_id', ConsumerStatus::ACTIVATE->value)
            ->where('connection_type_id', ConnectionType::POSTPAID->value)
            ->with(['statusHistory' => function ($q) {
                $q->where('status_id', ConsumerStatus::ACTIVATE->value)->latest()->limit(1);
            }])->first();
        if($consumer) {
            // 2. Get latest gas invoice if exists
            $invoice = BillInvoice::where('consumer_id', $id)->where('type_id', InvoiceType::GAS_BILL->value)->whereNot('status_id', InvoiceStatus::CANCEL->value)->latest()->first();
            $start_date = (!empty($invoice)) ? $invoice->consumption->date_to->format('Y-m-d') : ($consumer->statusHistory->first()->created_at->format('Y-m-d'));
            $end_date = date('Y-m-d');
            $bill_days = Carbon::parse($start_date)->diffInDays($end_date);

            // 3. Get the gas price for the billing
            // Get the price details
            $prices = collect();
            $minEffectiveFrom = PriceHistory::where('district_id', $consumer->district_id)
                ->where('segment_id', $consumer->segment_id)
                ->where('effective_from', '<=', $start_date)
                ->max('effective_from');
            if ($minEffectiveFrom) {
                $prices = PriceHistory::where('district_id', $consumer->district_id)
                    ->where('segment_id', $consumer->segment_id)
                    ->where('effective_from', '<=', $end_date)
                    ->where('effective_from', '>=', $minEffectiveFrom)
                    ->orderBy('effective_from')->get();
            }

            // Render output
            return view('billing.gas-bill.create', [
                'consumer' => $consumer, 
                'invoice' => $invoice, 
                'prices' => $prices, 
                'bill_days' => $bill_days,
            ]);
        }
        else {
            // Consumer not found or not in active status.
            abort(403, 'Invalid consumer for billing');
        }
    }

    /**
     * Save gas invoice
     */
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'id' => 'required',
            'end_reading' => 'required',
        ]);
        if($request->end_reading < $request->start_reading)
            {
                throw ValidationException::withMessages(['end_read_err' => 'End reading must be greater than start reading.']);
                return;
            }
        // 2. Prepare consumption data
        $total_consumption = ($request->end_reading - $request->start_reading);
        $old_consumption = (float)$request->old_consumption;
        $total_scms = round(($total_consumption+$old_consumption), 3);
        $cf = Constants::CORRECTION_FACTOR->value;
        $net_consumption = round(($total_scms * $cf),3);
        // 3. Get the consumer details
        $consumer = Consumer::where('id', $request->id)
            ->where('status_id', ConsumerStatus::ACTIVATE->value)
            ->where('connection_type_id', ConnectionType::POSTPAID->value)
            ->with(['statusHistory' => function ($q) {
                $q->where('status_id', ConsumerStatus::ACTIVATE->value)->latest()->limit(1);
            }])->first();
        // 4. checking for meter replacement
        $meterChange = $consumer->meterChanges()->where('status_id', MeterChange::PENDING->value)->first();
        // 5. Get the price details
        // first fetch the price record, if the price is changed before the start date.
        $prices = collect();
        $minEffectiveFrom = PriceHistory::where('district_id', $consumer->district_id)
            ->where('segment_id', $consumer->segment_id)
            ->where('effective_from', '<=', $request->start_date)
            ->max('effective_from');
        // fetch the price records, with reference of the above query result and end date.
        if ($minEffectiveFrom) {
            $prices = PriceHistory::where('district_id', $consumer->district_id)
                ->where('segment_id', $consumer->segment_id)
                ->where('effective_from', '<=', $request->end_date)
                ->where('effective_from', '>=', $minEffectiveFrom)
                ->orderBy('effective_from')->get();
        }
        // return error message, if there is no price records.
        if($prices->isEmpty()) {
            $request->validate([
                'cust_err_msg' => ['required' => "No Prices found."],
            ]);
        }
        // If total consumption is ZERO (zero billing.)
        if ($net_consumption <= 0) {
            $invoice_resp = $this->zeroInvoice($consumer, $prices, $request, $meterChange?->id);
        }
        // If there is single price record.
        elseif ($prices->count() == 1) {
            $invoice_resp = $this->singlePriceInvoice($consumer, $prices->first(), $request, $meterChange?->id);
        }
        // If there is multiple price change records.
        else {
            $invoice_resp = $this->multiPriceInvoice($consumer, $prices, $request, $meterChange?->id);
        }
        // SMS Notification
        $sms_response = SmsService::dispatch($consumer, new GasbillSmsNotification([
            'crn' => $consumer->crn, 
            'total_price' => $invoice_resp['total_price'], 
            'total_reading' => $invoice_resp['net_consumption'], 
            'invoice_no' => $invoice_resp['invoice_number'],
            'due_date' => $invoice_resp['due_date'],
        ]));
        if($invoice_resp) {   
            // Response Message
            return response()->json([
                'success' => 'Gas Invoice Created Successfully with invoice number ' . $invoice_resp['invoice_number'] . ', click <a href="'.url('reports/invoices/list').'">here</a> to see all invoices.'
            ]);
        }
    }

    /**
     * Zero consumption invoice preperation.
     * 
     */
    public function zeroInvoice($consumer, $prices, $request, $meterChangeId)
    {
        // 1. Prepare billing data
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $total_no_days = Carbon::parse($start_date)->diffInDays($end_date);
        $tax_value = $prices->last()->tax_value; // tax percentage.

        // 2. bill invoice array
        $invoice['invoice'] = [
            'type_id' => InvoiceType::GAS_BILL->value, //1 => Gas Invoice
            'consumer_id' => $consumer->id,
            'invoice_date' => Carbon::now()->format('Y-m-d'),
            'base_amount' => 0,
            'taxable_amount' => 0,
            'tax_id'=> TaxType::VAT->value,
            'tax_value' => $tax_value,
            'tax_amount' => 0,
            'total_amount' => 0,
            'payable_amount' => 0,
            'paid_amount' => 0,
            'balance_amount' => 0,
            'due_date' => Carbon::now()->addDays((int)Constants::DPNG_DUEDAYS->value)->format('Y-m-d'),
            'status_id' => InvoiceStatus::PAID->value, // paid
            'created_by' => Auth::id()
        ];

        // 3. Invoice Consumption array 
        $invoice['consumption'] = [
            'meter_id' => $consumer->activemeter->id,
            'date_from' => $start_date,
            'date_to' => $end_date,
            'days' => $total_no_days,
            'prev_reading' => $request->start_reading,
            'curr_reading' => $request->end_reading,
            'consumption' => 0,
            'cf' => Constants::CORRECTION_FACTOR->value,
            'old_consumption' => 0,
            'net_consumption' => 0,
            'unit_price' => $prices->avg('basic_price'), // average price.
            'meter_change_id' => $meterChangeId,
            'total_price' => 0,
            'file_id' => NULL,
        ];
        // 4. Calling of insertion method from the same controller. 
        $inv_resp = $this->invoiceInsert($consumer, $invoice);
        if($inv_resp)
        {
            // Create payment record for THIS invoice
            PaymentService::create([
                'invoice_id'      => $inv_resp['invoice_id'],
                'payment_date'    => date('Y-m-d'),
                'payment_type_id' => PaymentType::CASH_PAYMENT->value,
                'transaction_id'  => "CASH",
                'amount'          => 0,
                'balance'         => 0,
                'status_id'       => PaymentStatus::COMPLETED->value,
                'notes'           => NULL,
                'created_by'      => Auth::id(),
            ]);   
        }
        return $inv_resp;
    }

    /**
     * Invoice preperation for single price record.
     * 
     */
    public function singlePriceInvoice($consumer, $price, $request, $meterChangeId)
    {
        // 1. Prepare billing data
        $inv_base_amt = $inv_tax_amt = $inv_total = 0;
        $start_date = $start_date_1 =  $request->start_date;
        $end_date = $request->end_date;
        $total_no_days = Carbon::parse($start_date)->diffInDays($end_date);
        $total_consumption = ($request->end_reading - $request->start_reading);
        $old_consumption = (float)$request->old_consumption;
        $total_scms = round(($total_consumption+$old_consumption), 3);
        $cf = Constants::CORRECTION_FACTOR->value;
        $net_consumption = round(($total_scms * $cf),3);
        $inv_base_amt = round((($net_consumption * $cf) * $price->basic_price), 2);
        $tax_value = $price->tax_value; // tax percentage.
        $inv_tax_amt = round((($inv_base_amt * $tax_value) / 100), 2);
        $inv_total = $inv_base_amt + $inv_tax_amt;

        // 2. Invoice consumption details array preparation.
        $invoice['consumption_details'][] = [
            'price_history_id' => $price->id,
            'days' => $total_no_days,
            'consumption' => $total_scms,
            'cf' => $cf,
            'unit_price' => $price->basic_price,
            'total_price' => $inv_base_amt,
        ];

        // 3. bill invoice array
        $invoice['invoice'] = [
            'type_id' => InvoiceType::GAS_BILL->value, //1 => Gas Invoice
            'consumer_id' => $consumer->id,
            'invoice_date' => Carbon::now()->format('Y-m-d'),
            'base_amount' => $inv_base_amt,
            'taxable_amount' => $inv_base_amt,
            'tax_id'=> TaxType::VAT->value,
            'tax_value' => $tax_value,
            'tax_amount' => $inv_tax_amt,
            'total_amount' => $inv_total,
            'payable_amount' => $inv_total,
            'paid_amount' => NULL,
            'balance_amount' => $inv_total,
            'due_date' => Carbon::now()->addDays((int)Constants::DPNG_DUEDAYS->value)->format('Y-m-d'),
            'status_id' => InvoiceStatus::NOT_PAID->value, // Not paid
            'created_by' => Auth::id()
        ];

        // 4. Invoice Consumption array
        $invoice['consumption'] = [
            'meter_id' => $consumer->activemeter->id,
            'date_from' => $start_date,
            'date_to' => $end_date,
            'days' => $total_no_days,
            'prev_reading' => $request->start_reading,
            'curr_reading' => $request->end_reading,
            'consumption' => $total_consumption,
            'cf' => $cf,
            'old_consumption' => $old_consumption,
            'net_consumption' => $net_consumption,
            'unit_price' => $price->basic_price, // single price.
            'meter_change_id' => $meterChangeId,
            'total_price' => $inv_base_amt,
            'file_id' => NULL,
        ];
        // 5. Calling of insertion method from the same controller.
        $inv_resp = $this->invoiceInsert($consumer, $invoice);
        return $inv_resp;
    }

    /**
     * Invoice preperation for multiple price records.
     * 
     */
    public function multiPriceInvoice($consumer, $prices, $request, $meterChangeId)
    {   
        // 1. Prepare billing data
        $start_date = $start_date_1 =  $request->start_date;
        $end_date = $request->end_date;
        $total_no_days = Carbon::parse($start_date)->diffInDays($end_date);
        $total_consumption = ($request->end_reading - $request->start_reading);
        $old_consumption = (float)$request->old_consumption;
        $total_scms = round(($total_consumption+$old_consumption), 3);
        $scm_per_day = ($total_scms/$total_no_days);
        $cf = Constants::CORRECTION_FACTOR->value;
        $net_consumption = round(($total_scms * $cf),3);

        $p_price = $inv_base_amt = $inv_tax_amt = $inv_total = 0;
        $inv_consmp_details = [];
        foreach ($prices as $key => $price) {
            // If next slab exists, it starts a new price
            $end_date_1 = isset($prices[$key + 1]) ? Carbon::parse($prices[$key + 1]->effective_from)->subDay() : Carbon::parse($request->end_date);
            $no_days = Carbon::parse($start_date_1)->diffInDays($end_date_1);
            $consmp_breakup = ($no_days*$scm_per_day);
            $p_price += $price['basic_price'];
            $base_amot_1 = round((($consmp_breakup * $cf) * $price['basic_price']), 2);
            $inv_base_amt += $base_amot_1;

            
            $invoice['consumption_details'][] = [
                'price_history_id' => $price['id'],
                'days' => $no_days,
                'consumption' => $consmp_breakup,
                'cf' => $cf,
                'unit_price' => $price['basic_price'],
                'total_price' => $base_amot_1,
            ];
            $start_date_1 = $end_date_1; 
        }
        $avg_price = $p_price / count($prices);
        $tax_value = $prices->last()->tax_value; // tax percentage.
        $inv_tax_amt =  round((($inv_base_amt * $tax_value) / 100), 2);
        $inv_total =  $inv_base_amt + $inv_tax_amt;
                
        // bill invoice array
        $invoice['invoice'] = [
            'type_id' => InvoiceType::GAS_BILL->value, //1 => Gas Invoice
            'consumer_id' => $consumer->id,
            'invoice_date' => Carbon::now()->format('Y-m-d'),
            'base_amount' => $inv_base_amt,
            'taxable_amount' => $inv_base_amt,
            'tax_id'=> TaxType::VAT->value,
            'tax_value' => $tax_value,
            'tax_amount' => $inv_tax_amt,
            'total_amount' => $inv_total,
            'payable_amount' => $inv_total,
            'paid_amount' => NULL,
            'balance_amount' => $inv_total,
            'due_date' => Carbon::now()->addDays((int)Constants::DPNG_DUEDAYS->value)->format('Y-m-d'),
            'status_id' => InvoiceStatus::NOT_PAID->value, // Not paid
            'created_by' => Auth::id()
        ];

        $invoice['consumption'] = [
            'meter_id' => $consumer->activemeter->id,
            'date_from' => $start_date,
            'date_to' => $end_date,
            'days' => $total_no_days,
            'prev_reading' => $request->start_reading,
            'curr_reading' => $request->end_reading,
            'consumption' => $total_consumption,
            'cf' => $cf,
            'old_consumption' => $old_consumption,
            'net_consumption' => $net_consumption,
            'unit_price' => $avg_price, // average price.
            'meter_change_id' => $meterChangeId,
            'total_price' => $inv_base_amt,
            'file_id' => NULL,
        ];

        // 5. Calling of insertion method from the same controller.
        $inv_resp = $this->invoiceInsert($consumer, $invoice);
        return $inv_resp;
    }
    
    /** 
     * Common function for the invoice generation.
     */ 
    public function invoiceInsert($consumer, $invoice_data)
    {
        // print "<pre>"; print_r($invoice_data); exit;
        // 1. Invoice number generation via service.
        $inv_number = InvoiceService::generateNumber($consumer->ga->state_id, 1);
        // 2. Invoice insertion from the data received (excl. invoice number).
        $invoice_data['invoice']['invoice_number'] = $inv_number;
        $inv_insert = BillInvoice::create($invoice_data['invoice']);
        
        if($inv_insert) {
            // 3. save the invoice id column with new consumption object.
            $invoice_data['consumption']['invoice_id'] = $inv_insert->id;
            // 4. Invoice Consumption array insertion (excl. invoice id).
            $inv_cons = BillInvoiceConsumption::create($invoice_data['consumption']);
            // 5. Update the pending meter status to complete.
            if (!empty($invoice_data['consumption']['meter_change_id'])) {
                $consumer->meterChanges()->where('id', $invoice_data['consumption']['meter_change_id'])->update(['status_id' => MeterChange::CLOSED->value]);
            }
            // 6.. Attach consumption_id to consumption details.
            if($inv_cons and !empty($invoice_data['consumption_details'])) {
                foreach ($invoice_data['consumption_details'] as &$detail) {
                    $detail['invoice_consumption_id'] = $inv_cons->id;
                }
                BillInvoiceConsumptionDetails::insert($invoice_data['consumption_details']);
            }
            // Ledger Service.
            $ledger_record = LedgerService::create([
                'model' => $inv_insert,
                'consumer_id' => $inv_insert->consumer_id,
                'amount' => ($invoice_data['invoice']['total_amount']),
            ], 'dr');

            // dependent invoice creation (SD EMI / Rental)
            $scheme = $consumer->scheme;
            if ($scheme) {
                // EMI INVOICE
                if ($scheme->emi_amount > 0 and $scheme->security_deposit > 0 and $scheme->status == 0) {
                    DependentInvoiceService::sdEmiCreate($consumer, $inv_insert);
                }
                // RENTAL INVOICE (only if EMI not applicable)
                elseif ($scheme->rental_amount > 0 and $scheme->status == 0) {
                    DependentInvoiceService::rentalInvCreate($consumer, $inv_insert);
                }
            }
        }
        return [
            'invoice_id' => $inv_insert->id, 
            'invoice_number' => $inv_number, 
            'net_consumption' => $invoice_data['consumption']['net_consumption'],
            'total_price' => $invoice_data['invoice']['payable_amount'],
            'due_date' => $invoice_data['invoice']['due_date'],
        ];
    }
}
