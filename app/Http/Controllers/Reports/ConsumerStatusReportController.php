<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Master\ConnectionType;
use App\Models\Master\Ga;
use App\Models\Master\Segment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsumerStatusReportController extends Controller
{
    /**
     * Index Page
     */
    public function index(Request $request)
    {
        // dd($request->all());
        // Prepare params
        $from = Carbon::parse($request->date_from)->startOfDay();
        $to   = Carbon::parse($request->date_to)->endOfDay();
        // Get data
        $reports = ConsumerStatus::join('cns_consumers', 'cns_consumers.id', '=', 'cns_consumer_status.consumer_id')
            ->join('mst_cns_status', 'mst_cns_status.id', '=', 'cns_consumer_status.status_id')
            ->join('mst_gas', 'mst_gas.id', '=', 'cns_consumers.ga_id')
            ->select('mst_gas.name as ga_name', 'mst_cns_status.name as status_name', 'cns_consumers.fname' ,'cns_consumers.lname', 'cns_consumers.crn', 'cns_consumers.t_crn', 'cns_consumers.connection_type_id', 'cns_consumers.segment_id', 'cns_consumer_status.created_at as status_date')
            ->whereBetween('cns_consumer_status.created_at', [$from, $to])
            ->when(($request->has('connection_type_id') AND !empty($request->connection_type_id)), function($q) use($request) {
                $q->where('cns_consumers.connection_type_id', $request->connection_type_id);
            })
            ->when(($request->has('segment_id') AND !empty($request->segment_id)), function($q) use($request) {
                $q->where('cns_consumers.segment_id', $request->segment_id);
            })
            ->where(['cns_consumers.ga_id' => $request->ga_id, 'cns_consumer_status.status_id' => $request->status_id])
            ->orderBy('cns_consumer_status.created_at', 'desc')
            ->paginate(20)->withQueryString();
            // dd($reports);
        // Render output
        if($request->ajax() and $request->page >= 1) {
            return view('reports.consumer.status-report.list-body', ['reports' => $reports]);
        }
        return view('reports.consumer.status-report.list', ['reports' => $reports]);
    }
}