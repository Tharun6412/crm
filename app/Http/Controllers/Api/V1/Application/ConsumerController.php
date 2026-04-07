<?php

namespace App\Http\Controllers\Api\V1\Application;

use App\Enums\ConnectionType;
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
        if(!empty($request->key) OR !empty($request->cns_status) OR !empty($request->connection_type_id) OR !empty($request->geo_area) OR !empty($request->segments)) {            
            $consumers_q = Consumer::with(['ga:id,code,name', 'status:id,name'])->select('id', 'crn', 'fname', 'lname', 'ga_id', 'status_id', 'segment_id', 'connection_type_id')
                ->when((!isApiAdmin() AND !isApiSuperAdmin() AND !isApiFullAccess()), function ($q) use($request) {
                    $q->whereIn('ga_id', $request->user()->ga()->pluck('ga_id')->toArray());
                })
                ->when($request->has('key'), function ($q) use($request) {
                    $q->whereAny(['crn', 'fname', 'lname', 'email', 'phone'], 'like', '%' . $request->key . '%');
                })
                ->when($request->has('segments') and !empty($request->segments), function ($q) use($request) {
                    $q->whereIn('segment_id', (array) $request->segments);
                })
                ->when($request->has('connection_type_id') and !empty($request->connection_type_id), function ($q) use($request) {
                    $q->whereIn('connection_type_id', (array) $request->connection_type_id);
                })
                ->when($request->has('geo_area') and !empty($request->geo_area), function ($q) use($request) {
                    $q->whereIn('ga_id', (array) $request->geo_area);
                })
                ->when($request->has('cns_status') and !empty($request->cns_status), function ($q) use($request) {
                    $q->whereIn('status_id', (array) $request->cns_status);
                })
                ->paginate(10);
                $consumers = $this->apiPagination($consumers_q);
                // Response
                return response()->json(['consumers' => $consumers, 'user' => $request->user()->isAdmin()], 200);
        }else {
            return response()->json(['message' => 'Please select consumer number'], 422);
        }
    }

    /**
     * Consumer details
     */
    public function details(Request $request, $id)
    {
        // Find Consumer
        $consumer = Consumer::with([
            'state:id,name',
            'ga:id,name',
            'district:id,name',
            'ca:id,name',
            'statusHistory:id,consumer_id,lat,lng,status_id,created_by,created_at',
            'statusHistory.status:id,name', 
            'scheme',
            'scheme.scheme:id,name,registration,min_payment',
            'sdPayment',
            'sdPayment.paymentType:id,name',
            'activeMeter:id,consumer_id,meter_no,meter_serial_no,initial_reading,status',
            'activeMeter.meterStatus:id,name',
            'consumerData:id,consumer_id,lat,lng'
        ])->when((!isApiAdmin() AND !isApiSuperAdmin() AND !isApiFullAccess()), function ($q) use($request) {
            $q->whereIn('ga_id', $request->user()->ga()->pluck('ga_id')->toArray());
        })->find($id);
        $consumer->mobile = maskNumber($consumer->phone);
        $consumer->aadhar_val = maskNumber($consumer->aadhar);
        unset($consumer->phone, $consumer->aadhar);

        // Abort if consumer not found
        if (! $consumer) {
            return response()->json(['error' => 'Consumer not found'], 404);
        }
        // Get Consumer Outstanding balance + security Deposit balance
        $invoices = $consumer->invoices()->select('type_id','balance_amount')->get();
        $gasbill = $invoices->where('type_id', 1)->sum('balance_amount');
        $invoice = $invoices->where('type_id', '!=', 1)->sum('balance_amount');
        if($consumer->connection_type_id == ConnectionType::PREPAID->value) {
            $balance = [
                'sd_amount' => numberFormat($consumer->scheme->balance ?? 0, 2),
                'recharge_balance' => numberFormat($consumer->prepaidData->balance ?? 0, 2),
            ];
        }else {
            $balance = [
                'sd_amount' => numberFormat($consumer->scheme->balance ?? 0, 2),
                'gas_bills' => numberFormat($gasbill, 2),
                'invoices' => numberFormat($invoice, 2),
                'outstanding' => numberFormat(($consumer->scheme->balance ?? 0) + $gasbill + $invoice, 2),
            ];
        }
        // response
        return response()->json([
            'consumer' => $consumer,
            'outstanding' => $balance,
        ], 200);
    }
}