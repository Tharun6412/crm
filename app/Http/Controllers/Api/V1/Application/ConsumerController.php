<?php

namespace App\Http\Controllers\Api\V1\Application;

use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillInvoice;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ConsumerController extends Controller
{
    /**
     * Adjust pagination
     */
    use ApiResponse;

    /**
     * List
     */
    public function list(Request $request)
    {
        // Get consumers list
        $consumers_q = Consumer::with(['ga:id,code,name', 'status:id,name'])->select('id', 'crn', 'fname', 'lname', 'ga_id', 'status_id')
            ->when((!$request->user()->isAdmin() AND !$request->user()->isSuperAdmin()), function ($q) use($request) {
                $q->whereIn('ga_id', $request->user()->ga()->pluck('ga_id')->toArray());
            })
            ->when($request->has('key'), function ($q) use($request) {
                $q->whereAny(['crn', 'fname', 'lname', 'email', 'phone'], 'like', '%' . $request->key . '%');
            })
            ->when($request->has('segments'), function ($q) use($request) {
                $q->whereIn('segment_id', $request->segments);
            })
            ->when($request->has('connection_type_id'), function ($q) use($request) {
                $q->whereIn('connection_type_id', $request->connection_type_id);
            })
            ->when($request->has('geo_area'), function ($q) use($request) {
                $q->whereIn('ga_id', $request->geo_area);
            })
            ->when($request->has('cns_status'), function ($q) use($request) {
                $q->whereIn('status_id', $request->cns_status);
            })
            ->paginate(10);
            $consumers = $this->apiPagination($consumers_q);
        
        return response()->json(['consumers' => $consumers, 'user' => $request->user()->isAdmin()], 200);
    }

    /**
     * Consumer details
     */
    public function details(Request $request, $id)
    {
        // Find Consumer
        $consumer = Consumer::with(['scheme', 'sdPayment'])->when((!$request->user()->isAdmin() AND !$request->user()->isSuperAdmin()), function ($q) use($request) {
                $q->whereIn('ga_id', $request->user()->ga()->pluck('ga_id')->toArray());
            })->find($id);

        // Abort if consumer not found
        if (! $consumer) {
            return response()->json(['error' => 'Consumer not found'], 403);
        }
        // Get consumer details
        return response()->json([
            'consumer' => $consumer->makeHidden(['scheme', 'sdPayment']),
            'scheme_details' => $consumer->scheme,
            'sd_paid_history' => $consumer->sdPayment,
            // 'consumer_invoices' => BillInvoice::where('consumer_id', $id)->get(),
        ], 200);
    }
}