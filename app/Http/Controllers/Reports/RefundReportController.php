<?php
namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Consumer\ConsumerRefund;
use App\Models\Master\Ga;
use App\Models\Master\RefundStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundReportController extends Controller
{
    /**
     * Index Method
     */
    public function index(Request $request)
    {
        $geo_areas = Ga::where('status', 1)->orderBy('position')->get();
        $refund_status = RefundStatus::all();
        $refund_data = [];
        $data = ConsumerRefund::select('cns_consumers.ga_id', 'ref_refunds.status_id', DB::raw('COUNT(*) as status_count'))
            ->join('cns_consumers', 'ref_refunds.consumer_id', '=', 'cns_consumers.id')
            ->groupBy('cns_consumers.ga_id', 'ref_refunds.status_id')
            ->get();
        // Array Preparation
        foreach($data as $refund_count) {
            $refund_data[$refund_count->ga_id][$refund_count->status_id] = $refund_count->status_count;
            // Initialize total if not exists
            if (!isset($refund_data[$refund_count->ga_id]['total_count'])) {
                $refund_data[$refund_count->ga_id]['total_count'] = 0;
            }
            $refund_data[$refund_count->ga_id]['total_count'] += $refund_data[$refund_count->ga_id][$refund_count->status_id];
        }
        // Response
        if($request->ajax()) {
            if(empty($request->filter_name)) {
                abort(422, 'Please select the button');
            }
            return view('reports.consumer.refund-report.list-body', [
                'geo_areas' => $geo_areas, 
                'refund_status' => $refund_status,
                'refund_data' => $refund_data
            ]);
        }
        return view('reports.consumer.refund-report.list');
    }
} 