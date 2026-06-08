<?php 

namespace App\Http\Controllers\Payments;

use App\Enums\Constants;
use App\Enums\InvoiceItem;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\SegmentType;
use App\Enums\TaxType;
use App\Helpers\ApiLogger;
use App\Http\Controllers\Controller;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\InvoiceItem as InvoiceInvoiceItem;
use App\Models\Master\PaymentType;
use App\Services\InvoiceService;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OldPaymentsController extends Controller
{
    /**
     * Function to generate LPC for old invoices.
     * @param int $id invoice id
     */
    public function addLpc(Request $request, $id)
    {
        $bill = BillInvoice::find($id);
        $lpc_exists = BillInvoice::where('parent_invoice_id',$bill->id)->where('type_id', InvoiceType::LATE_PAYMENT_CHARGES->value)->where('status_id', '!=', InvoiceStatus::CANCEL->value)->first();
        return view('payments.old-payments.add-lpc', ['bill' => $bill, 'lpc_exists'=> $lpc_exists]);
    }

    public function generateLpc(Request $request)
    {
        $gas_invoice = BillInvoice::find($request->invoice_id);
        $lpc_exists = BillInvoice::where('parent_invoice_id',$gas_invoice->id)->where('type_id', InvoiceType::LATE_PAYMENT_CHARGES->value)->where('status_id', '!=', InvoiceStatus::CANCEL->value)->first();
        if($gas_invoice)
        {
            if($lpc_exists) {
                throw ValidationException::withMessages(['add-old-invoice-lpc-error' => 'There is already one LPC, can not generate another!']);
                return;
            }
            else {
                if($gas_invoice->consumer->segment_id == SegmentType::DOMESTIC->value) {
                    $inv_item = InvoiceItem::DLPC->value;
                    $late_fee = Constants::DPNG_LPC->value;
                }else if($gas_invoice->consumer->segment_id == SegmentType::COMMERCIAL->value) {
                    $inv_item = InvoiceItem::CLPC->value;
                    $late_fee = Constants::CPNG_LPC->value;
                }else {
                    $inv_item = InvoiceItem::ILPC->value;
                    $late_fee = Constants::IPNG_LPC->value;
                }
                $tax_value = 18;
                $basic_amount = round(($late_fee * (100 / (100 + $tax_value))),2);
                $tax_amount = round(($late_fee - $basic_amount),2);
                
                // Invoice array preperation for invoice service.
                $invoice_data = [
                    'headers' => [
                        'type_id' => InvoiceType::LATE_PAYMENT_CHARGES->value,
                        'consumer_id' => $gas_invoice->consumer_id,
                        'invoice_date' => Carbon::parse($gas_invoice->due_date)->addDay(),
                        'invoice_number' => $gas_invoice->invoice_number."L",
                        'base_amount' => $basic_amount,
                        'taxable_amount' => $basic_amount,
                        'tax_id' => TaxType::GST->value,
                        'tax_value' => $tax_value,
                        'tax_amount' => $tax_amount,
                        'total_amount' => $late_fee,
                        'payable_amount' => $late_fee,
                        'paid_amount' => $late_fee,
                        'balance_amount' => 0,
                        'due_date' => Carbon::parse($gas_invoice->due_date)->addDay(),
                        'status_id' => InvoiceStatus::PAID->value, //1 =  paid
                        'parent_invoice_id' => $gas_invoice->id,
                        'created_by' => Auth::id(),
                        'created_at' => Carbon::parse($gas_invoice->due_date)->addDay()->toDateTimeString(),
                        'updated_at' => Carbon::parse($gas_invoice->due_date)->addDay()->toDateTimeString(),
                    ]
                ];
                // Generate Invoice with Invoice Service
                $inv_number = BillInvoice::create($invoice_data['headers']);
                if(!empty($inv_number)) {
                    // Invoice items array preperation.
                    $invoice_data = [
                        'items' => [
                            'invoice_id' => $inv_number->id,
                            'item_id' => $inv_item,
                            'quantity' => 1,
                            'unit_price' => $basic_amount,
                            'total_price' => $basic_amount,
                        ]
                    ];
                    $inv_item = InvoiceInvoiceItem::insert($invoice_data['items']);
                    return response()->json(['success' => 'LPC Invoice generated successfully']);
                }
                else {
                    return response()->json(['danger' => 'LPC Invoice generation failed']);
                }
            }
        }
        else {
            return response()->json(['danger' => 'Inovice not found']);
        }
    }
    /**
     * @param $request 
     * @param int $id
     */
    public function edit(Request $request, $id)
    {
        $bill = BillInvoice::find($id);
        $payment_types = PaymentType::all();

        return view('payments.old-payments.edit', ['bill' => $bill, 'payment_types' => $payment_types]);
    }

    /**
     * Update the payment
     * @param int $id
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'invoice_id' => 'required',
            'payment_type' => 'required',
            'transaction_no' => 'required',
            'payment_date' => 'required',
            'amount' => ['required', 'numeric', 'gt:0','max:10000']
            ]);
        
        PaymentService::create([
            'invoice_id'      => $request->invoice_id,
            'payment_date'    => Carbon::parse($request->payment_date)->toDateString(),
            'payment_type_id' => $request->payment_type,
            'transaction_id'  => $request->transaction_no,
            'amount'          => $request->amount,
            'balance'         => 0,
            'status_id'       => 1,
            'notes'           => $request->notes,
            'created_by'      => Auth::id(),
        ]);
        $gas_invoice = BillInvoice::find($id);
        try {
            $invoices = BillInvoice::where('consumer_id', $gas_invoice->consumer_id)
                ->where('prepaid', 1)
                ->whereIn('type_id', [
                    InvoiceType::GAS_BILL->value,
                    InvoiceType::LATE_PAYMENT_CHARGES->value,
                    InvoiceType::RENTAL_CHARGES->value,
                    InvoiceType::SD_EMI->value
                ])
                ->whereIn('status_id',[InvoiceStatus::PARTIALLY_PAID->value, InvoiceStatus::NOT_PAID->value])
                ->orderBy('invoice_date', 'asc')
                ->orderBy('id', 'asc')
                ->get([
                    'id',
                    'total_amount',
                    'payable_amount',
                    'paid_amount',
                    'balance_amount',
                    'status_id'
                ]);
            // dd($invoices);
            $remainingPayment = (float)$request->amount;

            foreach ($invoices as $invoice) {

                $alreadyPaid    = (float) $invoice->paid_amount;  
                $outstandingDue = (float) $invoice->balance_amount; 

                if ($remainingPayment <= 0) {
                    break;
                }

                if ($remainingPayment >= $outstandingDue) {

                    $newPaidAmount   = $alreadyPaid + $outstandingDue;
                    $newBalanceAmount = 0;
                    $paymentStatus   = InvoiceStatus::PAID->value;
                    $remainingPayment -= $outstandingDue;

                } else {
                    // Partial settlement
                    $newPaidAmount    = $alreadyPaid + $remainingPayment;
                    $newBalanceAmount = $outstandingDue - $remainingPayment;
                    $paymentStatus    = InvoiceStatus::PARTIALLY_PAID->value;
                    $remainingPayment = 0;
                }
                $invoice->update([
                        'paid_amount'       => $newPaidAmount,
                        'balance_amount'    => $newBalanceAmount,
                        'status_id'         => $paymentStatus,
                        's_paid_amount'     => $newPaidAmount,
                        's_balance'         => $newBalanceAmount,
                        's_payment_status'  => $paymentStatus,
                    ]);
            }

            DB::commit();
        
            ApiLogger::info('payment','Payment_settlement','Consumer reconciled', [
                'consumer_id' => $gas_invoice->consumer_id
            ]);
            return response()->json(['success' => 'Invoice payment inserted successfully']);

        } catch (\Exception $e) {

            DB::rollBack();

            ApiLogger::error('payment','Payment_settlement','Reconciliation failed', [
                'consumer_id' => $gas_invoice->consumer_id,
                'message' => $e->getMessage()
            ]);
            return response()->json(['danger' => 'Reconciliation failed']);
        }
    }
}