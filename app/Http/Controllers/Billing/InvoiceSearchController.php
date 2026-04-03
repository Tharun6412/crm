<?php

namespace App\Http\Controllers\Billing;

use App\Enums\ConsumerStatus;
use App\Enums\InvoiceStatus;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\BillInvoiceCancel;
use App\Services\InvoiceService;
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
            $invoices = BillInvoice::with([
                'consumer:id,crn,ga_id', 
                'status:id,name',
                'invoiceType:id,name'
            ])
            ->where('status_id', InvoiceStatus::NOT_PAID->value)
            ->where(function ($q) use ($request) {
                $q->whereHas('consumer', function ($q1) use ($request) {
                    // GA restriction
                    if (!isAdmin() && !isSuperAdmin() && !isFullAccess()) {
                        $q1->whereIn('ga_id', session('user')['gas']);
                    }
                    // CRN search
                    if ($request->search) {
                        $q1->where('crn', 'like', '%'.$request->search.'%');
                    }
                })
                ->orWhere('invoice_number', 'like','%'.$request->search.'%');
            })
            ->latest()
            ->limit(20)
            ->get();
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
            $invoices = BillInvoice::with(['consumer:id,crn,ga_id', 'status'])->where('status_id', InvoiceStatus::NOT_PAID->value)
                ->whereHas('consumer', function ($q) use ($request) {
                    if (!isAdmin() && !isSuperAdmin() && !isFullAccess()) {
                        $q->whereIn('ga_id', session('user')['gas']);
                    }
                })
                ->Where('invoice_number', 'like','%'. $request->search . '%')
                ->latest()->limit(20)->get();
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
        $result = InvoiceService::cancel($id, $request->notes);
        // Response
        if($result == 1) {
            return response()->json(['success' => 'Invoice cancelled successfully']);
        }else {
            return response()->json(['success' => 'Invoice cannot be cancelled']);
        }
    }
}