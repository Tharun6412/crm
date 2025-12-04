<?php
namespace App\Http\Controllers\Consumer;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DocumentCentre\DocumentUpload;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerDocument;
use App\Models\Consumer\ConsumerRefund;
use App\Models\Consumer\ConsumerRefundStatus;
use App\Models\Consumer\ConsumersScheme;
use App\Models\Consumer\ConsumersStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefundController extends Controller
{
    /**
     * Index method
     */
    public function index(Request $request)
    {
        echo "test method";
    }
    /**
     * Inititate Refund Form 
     */
    public function edit(Request $request, $id) 
    {
        $consumer_scheme = ConsumersScheme::where('consumer_id', $id)->first();
        return view('consumers.refund.create', [
            'id' => $id, 
            'consumer_scheme' => $consumer_scheme,
        ]);
    }

    /**
     * Initiating Refund Amount
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'disconnect_amt' => 'required|numeric|min:0',
        ]);
        // Refund Data 
        $add_refund = ConsumerRefund::create([
            'consumer_id' => $id,
            'request_no' => '',
            'sd_paid' => 0,
            'outstanding_amount' => 0,
            'disconnection_amount' => $request->disconnect_amt,
            'invoice_id' => '',
            'refund_amount' => 0,
            'status_id' => 1,
            'created_by' => Auth::id(),
        ]);
        // Refund Status
        ConsumerRefundStatus::create([
            'refund_id' => $add_refund->id,
            'status_id' => '1', //1 = Not Paid 
            'created_by' => Auth::id(),
        ]);
        // Response
        return response()->json(['success' => 'Consumer refund initiated Successfully.Go to <a href="'.url('consumers/pd').'">Consumers List</a>']);
    }
} 