<?php

namespace App\Http\Controllers\Billing;

use App\Enums\ConsumerStatus;
use App\Enums\InvoiceStatus;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\BillInvoiceCancel;
use App\Services\LedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceSearchController extends Controller
{
    /**
     * Quick Search 
     */
    public function search(Request $request)
    {
        if($request->ajax()) {
            if(empty($request->search)) {
                return response()->json(['message' => 'Please enter Invoice/CRN number'], 422);
            }
            // Get Invoices
            $invoices = BillInvoice::when((!isAdmin() && !isSuperAdmin()), function ($q) {
                $q->whereHas('consumer', function ($q) {
                    $q->whereIn('ga_id', session('user')['gas']);
                });
            })
            ->where(function ($q) use ($request) {
                $q->whereHas('consumer', function ($q) use ($request) {
                    $q->where('crn', 'like', '%' . $request->search . '%');
                })
                ->orWhere('invoice_number', 'like', '%' . $request->search . '%');
            })
            ->paginate(20)
            ->withQueryString();
            // Ajax Response
            return view('billing.invoices.list-body', ['invoices' => $invoices]);
        }
        // Response
        return view('billing.invoices.list');
    }

    /**
     * Invoice Cancel List
     */
    public function cancel(Request $request)
    {
        if($request->ajax()) {
            if(empty($request->search)) {
                return response()->json(['message' => 'Please enter Invoice number'], 422);
            }
            // Get Invoices
            $invoices = BillInvoice::where('invoice_number', 'like', '%'.$request->search.'%')
                ->where('status_id', InvoiceStatus::NOT_PAID->value)
                ->paginate(20)->withQueryString();
            // Ajax Response
            return view('billing.cancel-invoice.list-body', ['invoices' => $invoices]);
        }
        // Response
        return view('billing.cancel-invoice.list');
    }

    /**
     * Cancel Invoice 
     * @param $invoice_id
     */
    public function cancelInvoice(Request $request, $id)
    {
        $invoice = BillInvoice::find($id);
        return view('billing.cancel-invoice.edit', ['invoice' => $invoice]);
    }
    /**
     * Invoice Update
     * NOT_PAID -> CANCEL
     * @param $invoice_id
     */
    public function cancelInvoiceUpdate(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required',
        ]);
        $invoice = BillInvoice::find($id);
        // Add to Ledger Record
        $ledger_data = [
            'model' => $invoice,
            'consumer_id' => $invoice->consumer_id,
            'amount' => $invoice->balance_amount,
            ];
        $add_ledger = LedgerService::create($ledger_data, 'cr');
        // Bill Invoice Update
        $invoice->update([
            'paid_amount' => $invoice->paid_amount + $invoice->balance_amount,
            'balance_amount' => 0,
            'status_id' => InvoiceStatus::CANCEL->value,
            'created_by' => Auth::id(),
        ]);
        // Add Invoice Cancel Record
        BillInvoiceCancel::create([
            'invoice_id' => $id,
            'reason' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        // Response
        return response()->json(['success' => 'Invoice cancelled successfully']);
    }
}