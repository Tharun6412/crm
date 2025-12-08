<?php
namespace App\Http\Controllers\Consumer;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumersStatus;
use App\Services\InvoiceGeneration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcceptController extends Controller
{
    /**
     * Index method
     */
    public function index(Request $request)
    {
        $invoice_details = array(
            'type_id' => 1, //Service Invoice
            'consumer_id' => 1,
            'invoice_date' => '2025-12-06',
            'base_amount' => 470,
            'taxable_amount' => 30,
            'tax_id' => 1,
            'tax_value' => 18,
            'tax_amount' => 30,
            'total_amount' => 500,
            'paid_amount' => 500,
            'balance_amt' => 0,
            'status_id' => 1, //Paid
            'created_by' => Auth::id(),
        );
        $inv_number_details = array(
            'state' => 1,
            'inv_type' => 2,
            'state_code' => 'AP',
        );
        InvoiceGeneration::serviceInvoiceGenerate($invoice_details, $inv_number_details);
        echo "Invoice generated";
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
        ConsumersStatus::create([
            'consumer_id' => $id,
            'status_id' => $con_status,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        // Response
        return response()->json(['success' => 'Consumer status updated Successfully!']);
    }
} 