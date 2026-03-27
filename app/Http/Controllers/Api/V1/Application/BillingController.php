<?php

namespace App\Http\Controllers\Api\V1\Application;

use App\Enums\AwsPath;
use App\Enums\ConnectionType;
use App\Enums\Constants;
use App\Enums\ConsumerStatus;
use App\Enums\InvoiceStatus;
use App\Enums\TaxType;
use App\Enums\InvoiceType;
use App\Enums\MeterChange;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Master\DocumentCentre\DocumentUpload;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\BillInvoiceConsumption;
use App\Models\Invoice\BillInvoiceConsumptionDetails;
use App\Models\Master\PaymentType;
use App\Models\Master\PriceHistory;
use App\Notifications\Consumer\GasbillSmsNotification;
use App\Services\DependentInvoiceService;
use App\Services\InvoiceService;
use App\Services\LedgerService;
use App\Services\SmsService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    /**
     * Generate Gas bill
     */
    public function generateGasBill(Request $request, $id)
    {
        $consumer = Consumer::with([
            'statusHistory:id,consumer_id,status_id,created_at',
            'meter:id,consumer_id,file_id,meter_no,meter_serial_no,initial_reading,install_date,status',
            'scheme:id,consumer_id,security_deposit,consumption_deposit,total_deposit,emi_amount,rental_amount,paid_deposit,balance,status',
            'sdPayment:id,consumer_id,emi_no,amount,status_id,invoice_id,balance,created_at',
            'meterChanges:id,consumer_id,meter_id,prev_reading,end_reading,consumption,new_meter_id,status_id',
            'activeMeter:id,consumer_id,meter_no,meter_serial_no,initial_reading,status',
        ])->select('id', 'segment_id', 't_crn', 'crn', 'title', 'fname', 'lname', 'segment_id', 'district_id', 'state_id', 'ga_id')->where('id', $id)
            ->where('status_id', ConsumerStatus::ACTIVATE->value)->where('connection_type_id', ConnectionType::POSTPAID->value)->first();
        // dd($consumer);
        // Check consumer is billable
        if($consumer) {
            // 1. Get latest gas invoice if exists
            // $invoice = BillInvoice::where('consumer_id', $id)->where('type_id', 1)->latest()->first();
            $invoice = $consumer->invoices()->where('type_id', InvoiceType::GAS_BILL->value)->whereNot('status_id', InvoiceStatus::CANCEL->value)->latest()->first();
            $start_date = (!empty($invoice)) ? $invoice->consumption->date_to->format('Y-m-d') : ($consumer->statusHistory->where('status_id', ConsumerStatus::ACTIVATE->value)->sortByDesc('created_at')->first()?->created_at->format('Y-m-d'));
            $end_date = date('Y-m-d');
            $bill_days = Carbon::parse($start_date)->diffInDays($end_date);

            // 2. Get the gas price for the billing
            // Get the price details
            $prices = collect();
            $minEffectiveFrom = PriceHistory::where('district_id', $consumer->district_id)
                ->where('segment_id', $consumer->segment_id)
                ->where('effective_from', '<=', $start_date)
                ->max('effective_from');
            // fetch the price records, with reference of the above query result and end date.
            if ($minEffectiveFrom) {
                $prices = PriceHistory::where('district_id', $consumer->district_id)
                    ->where('segment_id', $consumer->segment_id)
                    ->where('effective_from', '<=', $end_date)
                    ->where('effective_from', '>=', $minEffectiveFrom)
                    ->orderBy('effective_from')->get();
            }

            // Render output
            return response()->json([
                'consumer' => $consumer, 
                'invoice' => $invoice, 
                'prices' => $prices, 
                'bill_days' => $bill_days,
            ], 200);
        }
        else {
            // Consumer not found or not in active status.
            // abort(403, 'Invalid consumer for billing');
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid consumer for billing'
            ], 403);
        }
    }

    /**
     * Storing of gas invoice function
     * @param end_reading float
     * @param dc_file document
     * @return json_response 
     */
    public function storeGasBill(Request $request, $id)
    {
        // 1. Validation
        $request->validate([
            // 'start_date' => 'required|date|before:end_date',
            // 'end_date' => 'required|date|after:start_date',
            // 'start_reading' => 'required|numeric|min:0',
            'end_reading' => 'required|numeric|min:0',
            ]);
        // 2. Get the consumer details
        $consumer = Consumer::where('id', $id)->where('status_id', ConsumerStatus::ACTIVATE->value)->where('connection_type_id', ConnectionType::POSTPAID->value)->first();
        // 3.  error response, if consumer not found.
        if (!$consumer) {
            return response()->json([
                'message' => 'Invalid or inactive consumer for billing'
            ], 403);
        }
        // 4. Data preperation.
        $invoice = $consumer->invoices()->where('type_id', InvoiceType::GAS_BILL->value)->whereNot('status_id', InvoiceStatus::CANCEL->value)->latest()->first();
        // 5. checking for meter replacement
        $meterChange = $consumer->meterChanges()->where('status_id', MeterChange::PENDING->value)->first();

        // Preparing consumption data internally.
        $start_date = (!empty($invoice)) ? $invoice->consumption->date_to->format('Y-m-d') : ($consumer->statusHistory->where('status_id', ConsumerStatus::ACTIVATE->value)->sortByDesc('created_at')->first()?->created_at->format('Y-m-d'));
        $end_date = date('Y-m-d');
        $invEndReading = (!empty($invoice) and $invoice->consumption()->exists()) ? $invoice->consumption->curr_reading : 0;
        if ($consumer->meterChanges()->where('status_id', MeterChange::PENDING->value)->exists()) {
            $startReading = $consumer->activeMeter->initial_reading;
            $old_consumption = $meterChange?->consumption;
        }
        else {
            $startReading = ($invEndReading > 0) ? $invEndReading : (($consumer->activeMeter->initial_reading >= 0) ? $consumer->activeMeter->initial_reading : "");
            $old_consumption = 0;
        }
        // adding of date columns to request
        $request->merge([
            'start_date' => $start_date,
            'end_date' => $end_date,
            'start_reading' => $startReading,
            'old_consumption' => $old_consumption,
        ]);
        $total_no_days = Carbon::parse($request->start_date)->diffInDays($request->end_date);
        $total_consumption = ($request->end_reading - $request->start_reading);
        $old_consumption = (float)$request->old_consumption;
        $total_scms = round(($total_consumption+$old_consumption), 3);
        $cf = Constants::CORRECTION_FACTOR->value;
        $net_consumption = round(($total_scms * $cf),3);

        // 6. Get the price details
        $prices = collect();
        $minEffectiveFrom = PriceHistory::where('district_id', $consumer->district_id)
            ->where('segment_id', $consumer->segment_id)
            ->where('effective_from', '<=', $start_date)
            ->max('effective_from');
        // 7. fetch the price records, with reference of the above query result and end date.
        if ($minEffectiveFrom) {
            $prices = PriceHistory::where('district_id', $consumer->district_id)
                ->where('segment_id', $consumer->segment_id)
                ->where('effective_from', '<=', $end_date)
                ->where('effective_from', '>=', $minEffectiveFrom)
                ->orderBy('effective_from')->get();
        }
        // 8. If there is no price in between the range.
        if($prices->isEmpty()) {
            // Response Message
            return response()->json(['message' => 'No price configuration found for the given date range'], 422);
        }
        // 9. Billing frequency should be minimun 10 days.
        if($total_no_days < 10) {
            return response()->json(['message' => 'Billing Frequency should be greater than equal to 10 days.'], 422);
        }
        // Checking for end reading  must be greater than start reading.
        if($request->end_reading < $startReading) {
            return response()->json(['message' => 'End reading should be greater than equal to start reading.'], 422);
        }        
        // 10. If total consumption is ZERO (zero billing.)
        if ($net_consumption <= 0) {
            $invoice_resp = $this->zeroInvoice($consumer, $prices, $request, $meterChange?->id);
        }
        // 11. If there is single price record.
        elseif ($prices->count() == 1) {
            $invoice_resp = $this->singlePriceInvoice($consumer, $prices->first(), $request, $meterChange?->id);
        }
        // 12. If there is multiple price change records.
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
                'success' => 'Gas Invoice Created Successfully with invoice number ' . $invoice_resp['invoice_number'] . ', click <a href="'.url('gasInvoices').'">here</a> to see all invoices.'
            ]);
        }
    }
    /**
     * Zero consumption invoice preperation.
     * @param consumer
     * @param prices
     * @param request
     * @param meterChange_id
     * @return array(invoice_id, invoice_number)
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
        ];
        // 4. Calling of insertion method from the same controller. 
        $inv_resp = $this->invoiceInsert($consumer, $invoice, $request);
        return $inv_resp;
    }

    /**
     * Invoice preperation for single price record.
     * @param consumer
     * @param prices
     * @param request
     * @param meterChange_id
     * @return array(invoice_id, invoice_number)
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
        ];
        // 5. Calling of insertion method from the same controller.
        $inv_resp = $this->invoiceInsert($consumer, $invoice, $request);
        return $inv_resp;
    }

    /**
     * Invoice preperation for multiple price records.
     * @param consumer
     * @param prices
     * @param request
     * @param meterChange_id
     * @return array(invoice_id, invoice_number)
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
            'total_price' => $inv_base_amt,
            'meter_change_id' => $meterChangeId,
        ];

        // 5. Calling of insertion method from the same controller.
        $inv_resp = $this->invoiceInsert($consumer, $invoice, $request);
        return $inv_resp;
    }
    
    /** 
     * Common function for the invoice generation.
     * @param consumer
     * @param invoice_array
     * @param request
     * @return array(invoice_id, invoice_number)
     */ 
    public function invoiceInsert($consumer, $invoice_data, $request)
    {
        // 1. Invoice number generation via service.
        $inv_number = InvoiceService::generateNumber($consumer->ga->state_id, 1);
        // 2. Invoice insertion from the data received (excl. invoice number).
        $invoice_data['invoice']['invoice_number'] = $inv_number;
        $inv_insert = BillInvoice::create($invoice_data['invoice']);
        
        if($inv_insert) {
            // Meter image upload.
            $doc_upload = DocumentUpload::upload($request, AwsPath::BILLS->value);
            // 3. save the invoice id column with new consumption object.
            $invoice_data['consumption']['invoice_id'] = $inv_insert->id;
            $invoice_data['consumption']['file_id'] = $doc_upload['file_id'];
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