<?php

namespace App\Http\Controllers\Payments;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\InvoicePayment;
use App\Models\Invoice\PaymentReversal;
use App\Services\LedgerService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentsSearchController extends Controller
{
    /**
     * Quick Search 
     */
    public function search(Request $request)
    {
        if($request->ajax()) {
            if(empty($request->search)) {
                return response()->json(['message' => 'Please enter Invoice number'], 422);
            }
            // Get Invoices
            $invoices = BillInvoice::whereHas('consumer', function($q) use($request) {
                $q->where('crn', 'like', '%'.$request->search.'%');
            })->orWhere('invoice_number', 'like', '%'.$request->search.'%')->paginate(20)->withQueryString();
            // Ajax Response
            return view('payments.invoices.list-body', ['invoices' => $invoices]);
        }
        // Response
        return view('payments.invoices.list');
    }

    /**
     * Quick Search 
     */
    public function reversal(Request $request)
    {
        if($request->ajax()) {
            if(empty($request->search)) {
                return response()->json(['message' => 'Please enter transaction number'], 422);
            }
            // Get Invoices
            $payments = InvoicePayment::whereHas('invoice', function($q) use($request) {
                $q->where('invoice_number', 'like', '%'.$request->search.'%');
            })
            ->orWhere('transaction_id', 'like' , '%'.$request->search.'%')
            ->where('status_id', PaymentStatus::COMPLETED->value)->paginate(20)->withQueryString();
            // Ajax Response
            return view('payments.reversal.list-body', ['payments' => $payments]);
        }
        // Response
        return view('payments.reversal.list');
    }

    /**
     * Reversal Invoice 
     * @param $payment_id
     */
    public function reversalPayment(Request $request, $id)
    {
        $payment = InvoicePayment::find($id);
        return view('payments.reversal.edit', ['payment' => $payment]);
    }
    /**
     * Payment Reversal Update
     * COMPLETED -> REVERSAL
     * @param $payment_id
     */
    public function reversalPaymentUpdate(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required',
        ]);
        // fetch Payment record
        $payment = InvoicePayment::find($id);
        // Call Payment Service - Reversal 
        $response = PaymentService::reversal($payment, $request->notes);
        // response
        if($response == 1) {
            return response()->json(['success' => 'Payment reversal completed successfully']);
        }else {
            return response()->json(['success' => 'Payment not reversed due to invalid details']);
        }
    }
}