<?php 

namespace App\Http\Controllers\Payments;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Helpers\ApiLogger;
use App\Http\Controllers\Controller;
use App\Models\Invoice\BillInvoice;
use App\Models\Master\PaymentType;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OldPaymentsController extends Controller
{
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