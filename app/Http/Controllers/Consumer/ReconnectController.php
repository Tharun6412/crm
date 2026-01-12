<?php
namespace App\Http\Controllers\Consumer;

use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\TaxType;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Master\BillInvoiceItem;
use App\Services\InvoiceService;
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
        $invoice_items[] = [
            'item_id' => $request->item_id,
            'quantity' => 1,
            'unit_price' => $inv_item->basic,
            'total_price' => $inv_item->basic,
            'created_at' => Carbon::now(),
        ];
        $invoice_data = [
            'config' => [
                'state_id' => $consumer->ga->state_id,
                'tax_id' => TaxType::GST->value, //GST = 2
            ],
            'headers' => [
                'type_id' => InvoiceType::SERVICE_INVOICE->value, //2 = Service Invoice
                'consumer_id' => $id,
                'invoice_date' => Carbon::now()->toDateString(),
                'base_amount' => $base_amt,
                'taxable_amount' => $base_amt,
                'tax_id' => TaxType::GST->value,
                'tax_value' => 18,
                'tax_amount' => $tax_amt,
                'total_amount' => $amt,
                'payable_amount' => $amt,
                'paid_amount' => null,
                'balance_amount' => $amt,
                'status_id' => InvoiceStatus::NOT_PAID->value, // Not Paid
                'created_by' => Auth::id(),
            ],
            'items' => $invoice_items,
        ];
        // Generate Invoice with Invoice Service
        $inv_number = InvoiceService::create($invoice_data);
        // Consumer Status Update
        $consumer->update([
            'status_id' => EnumsConsumerStatus::ACTIVATE->value,
        ]);
        // Adding to Status History
        ConsumerStatus::create([
            'consumer_id' => $id,
            'status_id' => EnumsConsumerStatus::RECONNECT->value,
            'notes' => !empty($request->notes) ? $request->notes : null,
            'created_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'Consumer reconnected successfully']);
    }
} 