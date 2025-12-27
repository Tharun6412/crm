<?php
namespace App\Http\Controllers\Consumer;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\InvoicePayment;
use App\Models\Invoice\Ledger;
use App\Services\LedgerService;
use Faker\Provider\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcceptController extends Controller
{
    /**
     * Index method
     */
    public function index(Request $request)
    {
        $invoice = BillInvoice::find(1);
        $payment = InvoicePayment::find(1);
            $ledger = Ledger::where('consumer_id', 1)->latest('id')->first();
            print "<pre>"; print_r($ledger->debit);exit;
        $data[] = [
            'model' => $invoice,
            'consumer_id' => 1,
            'description' => "test",
            'credit' => 100,
            'debit' => 0,
            'balance' => 0,
        ];
        $data[] = [
            'model' => $payment,
            'consumer_id' => 1,
            'description' => "test",
            'credit' => 100,
            'debit' => 0,
            'balance' => 0,
        ];
        LedgerService::create($data);
        return "Invoices Inserted";
    }
    /**
     * Consumer Scheme Accept State
     */
    public function edit(Request $request, $id) 
    {
        $consumer= Consumer::find($id);
        return view('consumers.accept.edit', [
            'consumer' => $consumer, 
            'id' => $id, 
        ]);
    }

    /**
     * Accept/Reject
     * Registered -> Accept/Reject
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|max:255',
            'status' => 'required',
        ]);
        // Consumer Status History 3= Accept, 9=Reject
        if($request->status == 1) {
            $con_status = 3;
            $status_val = "accepted";
        }else {
            $con_status = 9;
            $status_val = "rejected";
        }
        // Consumer Update
        Consumer::where('id', $id)->update([
            'status_id' => $con_status,
            'updated_by' => Auth::id(),
        ]);
        // Status History
        ConsumerStatus::create([
            'consumer_id' => $id,
            'status_id' => $con_status,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        // Response
        return response()->json(['success' => 'Consumer status updated Successfully!']);
    }
} 