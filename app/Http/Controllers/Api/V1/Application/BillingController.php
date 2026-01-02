<?php

namespace App\Http\Controllers\Api\V1\Application;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DocumentCentre\DocumentUpload;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\BillInvoiceConsumption;
use App\Models\Invoice\BillInvoiceConsumptionDetails;
use App\Models\Master\PaymentType;
use App\Models\Master\PriceHistory;
use App\Services\InvoiceService;
use App\Services\LedgerService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    /**
     * Generate Gas bill
     */
    public function generateGasBill(Request $request, $id)
    {
        $consumer = Consumer::where('id', $id)
            ->where('status_id', 6)
            ->with(['statusHistory' => function ($q) {
                $q->where('status_id', 6)->latest()->limit(1);
            }])->first();
        
        // Check consumer is billable
        if($consumer) {
            // 1. Get latest gas invoice if exists
            $invoice = BillInvoice::where('consumer_id', $id)->where('type_id', 1)->latest()->first();

            $start_date = (!empty($invoice)) ? $invoice->consumption->last()->date_to->format('Y-m-d') : ($consumer->statusHistory->first()->created_at->format('Y-m-d'));
            $end_date = date('Y-m-d');
            $bill_days = Carbon::parse($start_date)->diffInDays($end_date) + 1;

            // 2. Get the gas price for the billing
            $prices = PriceHistory::where('district_id', $consumer->district_id)
                ->where('segment_id', $consumer->segment_id)
                ->where(function ($q) use ($start_date, $end_date) {
                    $q->where('effective_from', '<=', $end_date)->where('effective_to', '>=', $start_date);
                })
                ->orderBy('effective_from')->get();
            
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
                'bill_days' => $bill_days
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
            'end_reading' => 'required',
            'start_reading' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        $start_date = $start_date_1 =  $request->start_date;
        $end_date = $request->end_date;
        // Get the consumer details
        $consumer = Consumer::where('id', $id)
            ->where('status_id', 6)
            ->with(['statusHistory' => function ($q) {
                $q->latest()->limit(1);
            }])->first();
        //     
        if (!$consumer) {
            return response()->json([
                'message' => 'Invalid or inactive consumer for billing'
            ], 403);
        }
        // Get the price details
        $prices = PriceHistory::where('district_id', $consumer->district_id)
            ->where('segment_id', $consumer->segment_id)
            ->where(function ($q) use ($start_date, $end_date) {
                $q->where('effective_from', '<=', $end_date)
                ->where('effective_to', '>=', $start_date);
            })
            ->orderBy('effective_from')->get()->toArray();

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
        $total_no_days = Carbon::parse($start_date)->diffInDays($end_date) + 1;

        $start_reading = (float)$request->start_reading;
        $end_reading = (float)$request->end_reading;
        $total_consumption = ($end_reading - $start_reading);
        $scm_per_day = ($total_consumption/$total_no_days);
        $cf = 1;
        $net_consumption = round(($total_consumption * $cf),3);
        $p_price = $inv_base_amt = $inv_tax_amt = $inv_total = 0;
        $p_id = NULL;
        $vat_percent = 0;
        foreach ($prices as $key => $price) {
            $end_date_1 = ($end_date > $price['effective_to']) ? $price['effective_to'] : $end_date;
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
        $inv_number = InvoiceService::generateNumber($consumer->ga->state_id, 1);
        $invoice_date = Carbon::now()->format('Y-m-d');
        $due_date = Carbon::now()->addDays(15)->format('Y-m-d');

        // Meter image upload.
        $doc_upload = DocumentUpload::upload($request, 'domestic');

        // bill invoice array
        $invoice_ar = [
            'type_id' => 1, //1 => Gas Invoice
            'consumer_id' => $consumer->id,
            'invoice_number' => $inv_number,
            'invoice_date' => $invoice_date,
            'base_amount' => $inv_base_amt,
            'taxable_amount' => $inv_base_amt,
            'tax_id'=> 2,
            'tax_value' => $vat_percent,
            'tax_amount' => $inv_tax_amt,
            'total_amount' => $inv_total,
            'paid_amount' => NULL,
            'balance_amount' => $inv_total,
            'due_date' => $due_date,
            'status_id' => 2, // Not paid
            'created_by' => Auth::id()
        ];
        $inv_insert = BillInvoice::create($invoice_ar);
        if($inv_insert) {
            //  Invoice Consumption array
            $inv_consumption = [
                'invoice_id' => $inv_insert->id,
                'meter_id' => $consumer->meter->id,
                'price_history_id' => $p_id,
                'date_from' => $start_date,
                'date_to' => $end_date,
                'days' => $total_no_days,
                'prev_reading' => $start_reading,
                'curr_reading' => $end_reading,
                'consumption' => $total_consumption,
                'cf' => $cf,
                'mater_change_id' => NULL,
                'old_comsumption' => NULL,
                'net_consumption' => $net_consumption,
                'unit_price' => $avg_price,
                'total_price' => $inv_total,
                'file_id' => $doc_upload['file_id'],
            ];
            $inv_cons = BillInvoiceConsumption::create($inv_consumption);
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
            // Prepare Ledger Data
            $data[] = [
                'model' => $inv_insert,
                'consumer_id' => $inv_insert->consumer_id,
                'description' => "Bill: Invoice Generated with Invoice No.",
                'credit' => NULL,
                'debit' => $inv_insert->total_amount,
                'balance' => $inv_insert->total_amount,
            ];
            // Add/insert into Ledger Report
            LedgerService::create($data, 'debit');

            // Response Message
            return response()->json([
                'success' => 'Gas Invoice Created Successfully with invoice number ' . $inv_number . ', click <a href="'.url('gasInvoices').'">here</a> to see all invoices.'
            ]);
        }
    }
}