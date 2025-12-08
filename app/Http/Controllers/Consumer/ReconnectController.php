<?php
namespace App\Http\Controllers\Consumer;
use App\Http\Controllers\Controller;
use App\Models\Consumer\CaCounter;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerSdPayment;
use App\Models\Consumer\ConsumersScheme;
use App\Models\Consumer\ConsumersStatus;
use App\Models\Invoice\InvoiceItem;
use App\Models\Master\BillInvoiceItem;
use App\Models\Master\ConsumerStatus;
use App\Models\Master\PaymentType;
use App\Services\InvoiceGeneration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReconnectController extends Controller
{
    /**
     * Index method
     */
    public function index(Request $request)
    {
        echo "test method";
    }
    /**
     * TO Get the consumer Details
     */
    public function edit(Request $request, $id) 
    {
        $consumer = Consumer::find($id);
        $inv_items= BillInvoiceItem::where('type_id', 1)->get();
        return view('consumers.reconnect.edit', [
            'consumer' => $consumer,
            'inv_items' => $inv_items,
        ]);
    }

    /**
     * Service Invoice Generation
     * Adding Invoice Item
     *  Reconnect => TD -> Activate
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'item_id' => 'required',
            'notes' => 'required',
        ]);
        $inv_item = BillInvoiceItem::find($request->item_id);
        $consumer = Consumer::find($id);
        // Service Invoice Generation
        // Calculations
        $amt = $inv_item->price;
        $gst_calculated_amt = 1.18; //(1+18%)
        $base_amt = round($amt/$gst_calculated_amt, 3);
        $tax_amt = round($amt - $base_amt, 3);
        $invoice_details = array(
            'type_id' => 1, //Service Invoice
            'consumer_id' => $id,
            'invoice_date' => Carbon::now()->toDateString(),
            'base_amount' => $base_amt,
            'taxable_amount' => $base_amt,
            'tax_id' => 1,
            'tax_value' => 18,
            'tax_amount' => $tax_amt,
            'total_amount' => $amt,
            'paid_amount' => $amt,
            'balance_amt' => 0,
            'status_id' => 1, //Paid
            'created_by' => Auth::id(),
        );
        $inv_number_details = array(
            'state_id' => $consumer->ga->state_id,
            'inv_type' => 1,
            'state_code' => $consumer->ga->state->code,
        );
        $create_invoice = InvoiceGeneration::serviceInvoiceGenerate($invoice_details, $inv_number_details);
        // Invoice item 
        InvoiceItem::create([
            'invoice_id' => $create_invoice,
            'item_id' => $request->item_id,
            'quantity' => 1,
            'unit_price' => $inv_item->price,
            'total_price' => $inv_item->price,
        ]);
        // Consumer Status Update
        $consumer->update([
            'status_id' => 6,
        ]);
        // Adding to Status History
        ConsumersStatus::create([
            'consumer_id' => $id,
            'status_id' => 10,
            'notes' => !empty($request->notes) ? $request->notes : null,
            'created_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'Consumer reconnected successfully']);
    }
} 