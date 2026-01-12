<?php

namespace App\Http\Controllers\Api\V1\Application;

use App\Enums\ConsumerStatus;
use App\Enums\InvoiceStatus;
use App\Enums\TaxType;
use App\Enums\InvoiceType;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DocumentCentre\DocumentUpload;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\BillInvoiceConsumption;
use App\Models\Invoice\BillInvoiceConsumptionDetails;
use App\Models\Master\PaymentType;
use App\Models\Master\PriceHistory;
use App\Services\DependentInvoiceService;
use App\Services\InvoiceService;
use App\Services\LedgerService;
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
            'invoices:id,consumer_id,invoice_date,invoice_number,type_id,base_amount,taxable_amount,tax_id,tax_amount,total_amount,credit_amount,payable_amount,balance_amount,paid_amount,due_date,status_id,parent_invoice_id',
        ])->select('id', 'segment_id', 't_crn', 'crn', 'title', 'fname', 'lname', 'segment_id', 'district_id', 'state_id', 'ga_id')->where('id', $id)
            ->where('status_id', ConsumerStatus::ACTIVATE->value)->first();
        // dd($consumer);
        // Check consumer is billable
        if($consumer) {
            // 1. Get latest gas invoice if exists
            // $invoice = BillInvoice::where('consumer_id', $id)->where('type_id', 1)->latest()->first();
            $invoice = $consumer->invoices()->where('type_id', 1)->latest()->first();
            $start_date = (!empty($invoice)) ? $invoice->consumption->date_to->format('Y-m-d') : ($consumer->statusHistory->first()->created_at->format('Y-m-d'));
            $end_date = date('Y-m-d');
            $bill_days = Carbon::parse($start_date)->diffInDays($end_date);

            // 2. Get the gas price for the billing
            // Get the price details
            $minEffectiveFrom = PriceHistory::where('district_id', $consumer->district_id)
                ->where('segment_id', $consumer->segment_id)
                ->where('effective_from', '<=', $start_date)
                ->max('effective_from');
            $prices = PriceHistory::where('district_id', $consumer->district_id)
                ->where('segment_id', $consumer->segment_id)
                ->where('effective_from', '<=', $end_date)
                ->where('effective_from', '>=', $minEffectiveFrom)
                ->orderBy('effective_from')
                ->get();
            // If no price changes found, fetch the latest single record
            if ($prices->isEmpty()) {
                $prices = PriceHistory::where('district_id', $consumer->district_id)
                    ->where('segment_id', $consumer->segment_id)
                    ->orderBy('effective_from', 'desc')
                    ->limit(1)
                    ->get();
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


    public function storeGasBill(Request $request, $id)
    {
        // Validation
        $request->validate([
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
            'end_reading' => 'numeric|min:0',
            'start_reading' => 'numeric|min:0',
        ]);
        $start_date = $start_date_1 =  $request->start_date;
        $end_date = $request->end_date;
        // Get the consumer details
        $consumer = Consumer::with([
            'statusHistory:id,consumer_id,status_id,created_at',
            'meter:id,consumer_id,file_id,meter_no,meter_serial_no,initial_reading,install_date,status',
            'scheme:id,consumer_id,security_deposit,consumption_deposit,total_deposit,emi_amount,rental_amount,paid_deposit,balance,status',
            'sdPayment:id,consumer_id,emi_no,amount,status_id,invoice_id,balance,created_at',
            'meterChanges:id,consumer_id,meter_id,prev_reading,end_reading,consumption,new_meter_id,status_id',
            'activeMeter:id,consumer_id,meter_no,meter_serial_no,initial_reading,status',
            'invoices:id,consumer_id,invoice_date,invoice_number,type_id,base_amount,taxable_amount,tax_id,tax_amount,total_amount,credit_amount,payable_amount,balance_amount,paid_amount,due_date,status_id,parent_invoice_id',
        ])->select('id', 'segment_id', 't_crn', 'crn', 'title', 'fname', 'lname', 'segment_id', 'district_id', 'state_id', 'ga_id')->where('id', $id)
            ->where('status_id', ConsumerStatus::ACTIVATE->value)->first();
        //     
        if (!$consumer) {
            return response()->json([
                'message' => 'Invalid or inactive consumer for billing'
            ], 403);
        }
        // checking for meter replacement
        $meterChange = $consumer->meterChanges()->where('status_id', 1)->first();
        // Get the price details
        $minEffectiveFrom = PriceHistory::where('district_id', $consumer->district_id)
            ->where('segment_id', $consumer->segment_id)
            ->where('effective_from', '<=', $start_date)
            ->max('effective_from');
        $prices = PriceHistory::where('district_id', $consumer->district_id)
            ->where('segment_id', $consumer->segment_id)
            ->where('effective_from', '<=', $end_date)
            ->where('effective_from', '>=', $minEffectiveFrom)
            ->orderBy('effective_from')
            ->get()->toArray();
        // If no price changes found, fetch the latest single record
        if (empty($prices)) {
            $prices = PriceHistory::where('district_id', $consumer->district_id)
                ->where('segment_id', $consumer->segment_id)
                ->orderBy('effective_from', 'desc')
                ->limit(1)
                ->get()->toArray();
        }
        // If there is no price in between the range.
        if(empty($prices)) {
            // Response Message
            return response()->json(['message' => 'No price configuration found for the given date range'], 422);
        }
        $total_no_days = Carbon::parse($start_date)->diffInDays($end_date);
        if($total_no_days < 10) {
            return response()->json(['message' => 'Billing Frequency should be greater than equal to 10 days.'], 422);
        }

        $start_reading = (float)$request->start_reading;
        $end_reading = (float)$request->end_reading;
        if ($end_reading < $start_reading) {
            return response()->json(['message' => 'End reading cannot be less than start reading'], 422);
        }

        $total_consumption = ($end_reading - $start_reading);
        $old_consumption = (float)$request->old_consumption;
        $total_scms = round(($total_consumption+$old_consumption), 3);
        $scm_per_day = ($total_scms/$total_no_days);
        $cf = 1;
        $net_consumption = round(($total_scms * $cf),3);
        $p_price = $inv_base_amt = $inv_tax_amt = $inv_total = 0;
        $p_id = NULL;
        $vat_percent = 0;
        $inv_consmp_details = [];
        foreach ($prices as $key => $price) {
            $end_date_1 = (isset($price['effective_to']) and ($end_date > $price['effective_to'])) ? $price['effective_to'] : $end_date;
            $no_days = Carbon::parse($start_date_1)->diffInDays($end_date_1);
            $consmp_breakup = ($no_days*$scm_per_day);
            $p_price += $price['basic_price'];
            $base_amot_1 = round((($consmp_breakup * $cf) * $price['basic_price']), 2);
            $tax_amot_1 = round((($base_amot_1 * $price['tax_value']) / 100), 2);
            $total_amot_1 = $base_amot_1 + $tax_amot_1;
            $inv_base_amt += $base_amot_1;
            $inv_tax_amt += $tax_amot_1;
            $inv_total += $total_amot_1; 
            $p_id = $price['id'];
            $vat_percent = $price['tax_value'];

            $inv_consmp_details[] = [
                'price_history_id' => $price['id'],
                'days' => $no_days,
                'consumption' => $consmp_breakup,
                'cf' => $cf,
                'unit_price' => $price['basic_price'],
                'total_price' => round(($price['basic_price'] * ($consmp_breakup * $cf)),2),
            ];
            $start_date_1 = $end_date_1; 
        }
        $avg_price = $p_price / count($prices); 
        
        // invoice number generation
        $inv_number = InvoiceService::generateNumber($consumer->state_id, 1);
        $invoice_date = Carbon::now()->format('Y-m-d');
        $due_date = Carbon::now()->addDays(15)->format('Y-m-d');
        // Meter image upload.
        $doc_upload = DocumentUpload::upload($request, 'domestic');

        // bill invoice array
        $invoice_ar = [
            'type_id' => InvoiceType::GAS_BILL->value, //1 => Gas Invoice
            'consumer_id' => $consumer->id,
            'invoice_number' => $inv_number,
            'invoice_date' => $invoice_date,
            'base_amount' => $inv_base_amt,
            'taxable_amount' => $inv_base_amt,
            'tax_id' => TaxType::GST->value,
            'tax_value' => $vat_percent,
            'tax_amount' => $inv_tax_amt,
            'total_amount' => $inv_total,
            'paid_amount' => NULL,
            'balance_amount' => $inv_total,
            'due_date' => $due_date,
            'status_id' => InvoiceStatus::NOT_PAID->value, // Not paid
            'created_by' => Auth::id()
        ];
        $inv_insert = BillInvoice::create($invoice_ar);
        if($inv_insert) {
            //  Invoice Consumption array
            $inv_consumption = [
                'invoice_id' => $inv_insert->id,
                'meter_id' => $consumer->activeMeter?->id,
                'price_history_id' => $p_id,
                'date_from' => $start_date,
                'date_to' => $end_date,
                'days' => $total_no_days,
                'prev_reading' => $start_reading,
                'curr_reading' => $end_reading,
                'consumption' => $total_consumption,
                'cf' => $cf,
                'mater_change_id' => NULL,
                'old_consumption' => $old_consumption,
                'net_consumption' => $net_consumption,
                'unit_price' => $avg_price,
                'total_price' => $inv_total,
                'meter_change_id' => $meterChange?->id,
                'file_id' => $doc_upload['file_id'],
            ];
            $inv_cons = BillInvoiceConsumption::create($inv_consumption);
            // Update the pending meter status to complete.
            if ($meterChange) {
                $meterChange->status_id = 2; // example: approved / processed
                $meterChange->save();
            }
            // Attach consumption_id to consumption details & bulk insert
            if($inv_cons and $net_consumption > 0) {
                $bulkRows = [];
                foreach ($inv_consmp_details as $detail) {
                    $bulkRows[] = [
                        'invoice_consumption_id'   => $inv_cons->id,
                        'price_history_id' => $detail['price_history_id'],
                        'days'             => $detail['days'],
                        'consumption'      => $detail['consumption'],
                        'cf'               => $cf,
                        'unit_price'       => $detail['unit_price'],
                        'total_price'      => $detail['total_price'],
                    ];
                }
                BillInvoiceConsumptionDetails::insert($bulkRows);
            }
            // Ledger Service.
            $ledger_record = LedgerService::create([
                'model' => $inv_insert,
                'consumer_id' => $inv_insert->consumer_id,
                'amount' => ($inv_total ?? 0),
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

            // Response Message
            return response()->json([
                'success' => 'Gas Invoice Created Successfully with invoice number ' . $inv_number . ', click <a href="'.url('gasInvoices').'">here</a> to see all invoices.'
            ]);
        }
    }
}