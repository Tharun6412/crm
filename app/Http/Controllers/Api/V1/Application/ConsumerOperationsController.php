<?php

namespace App\Http\Controllers\Api\V1\Application;

use App\Http\Controllers\Controller;
use App\Models\Consumer\CaCounter;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerRefund;
use App\Models\Consumer\ConsumerRefundStatus;
use App\Models\Consumer\ConsumerSdPayment;
use App\Models\Consumer\ConsumersScheme;
use App\Models\Consumer\ConsumersStatus;
use App\Models\Invoice\InvoicePayment;
use App\Services\InvoiceGeneration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsumerOperationsController extends Controller
{
    /**
     * Activate -> TD
     */
    public function tdisconnect(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|max:255',
        ]);
        // 7 = TD
        Consumer::where('id', $id)->update([
            'status_id' => 7,
            'updated_by' => Auth::id(),
        ]);
        // Consumer Status History
        ConsumersStatus::create([
            'consumer_id' => $id,
            'status_id' => 7,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        // Response
        return response()->json(['success' => 'Consumer Temporarily Disconnected'], 200);
    }
    
    /**
     * Permanently Disconnected
     * Activate -> PD
     * TD -> PD
     */
    public function pdisconnect(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|max:255',
        ]);
        // 8 = PD
        Consumer::where('id', $id)->update([
            'status_id' => 8,
            'updated_by' => Auth::id(),
        ]);
        // Consumer Status History
        ConsumersStatus::create([
            'consumer_id' => $id,
            'status_id' => 8,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        // Response
        return response()->json(['success' => 'Consumer Permanently Disconnected.'], 200);
    }
    
    /**
     * Refund Request Inititation
     * 1 => Request
     * Only if consumer is PD
     */
    public function refundRequest(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required',
        ]);
        // Refund Data
        $add_refund = ConsumerRefund::create([
            'consumer_id' => $id,
            'status_id' => 1, //Refund Request
            'created_by' => Auth::id(),
        ]);
        // Number Generation
        $request_number_create = "R".str_pad($add_refund->id, 6,0,STR_PAD_LEFT);
        ConsumerRefund::where('id', $add_refund->id)->update(['request_no' => $request_number_create]);
        // Refund Status
        ConsumerRefundStatus::create([
            'refund_id' => $add_refund->id,
            'status_id' => '1', //1 = Refund Request 
            'notes' => !empty($request->notes) ? $request->notes : null,
            'created_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'Consumer refund requested successfully']);
    }
}