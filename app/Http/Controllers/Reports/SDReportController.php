<?php

namespace App\Http\Controllers\Reports;

use App\Exports\Reports\SDReportExport;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerScheme;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Master\ConnectionType;
use App\Models\Master\Ga;
use App\Models\Master\Segment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SDReportController extends Controller
{
    /**
     * Index
     */
    public function index(Request $request)
    {
        // Get GeoAreas
        $geo_areas = Ga::where('status', 1)->orderBy('position')->get();
        if($request->ajax()) {
            // Validation
            if(empty($request->check_all) AND empty($request->date_from) AND empty($request->date_to)) {
                abort(422, 'please select');
            }
            $request->validate([
                'date_from' => 'nullable|date_format:d-m-Y',
                'date_to' => 'nullable|date_format:d-m-Y',
            ]);
            // Query to sum of the amounts between the dates
            $sd_amounts = ConsumerScheme::join('cns_consumers', 'cns_consumers.id', '=', 'cns_consumer_schemes.consumer_id')
                ->when(!isAdmin() && !isSuperAdmin(), function ($q) {
                    $q->whereIn('cns_consumers.ga_id', session('user')['gas']);
                })
                ->selectRaw('
                    cns_consumers.ga_id,
                    SUM(cns_consumer_schemes.total_deposit) as total_deposit,
                    SUM(cns_consumer_schemes.paid_deposit) as paid_deposit,
                    SUM(cns_consumer_schemes.balance) as balance
                ')
                ->when(empty($request->check_all) and (!empty($request->date_from) and !empty($request->date_to)), function($q) use($request) {
                    $q->whereBetween('cns_consumer_schemes.created_at', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()->toDateTimeString()]);
                })
                ->groupBy('cns_consumers.ga_id')
                ->get();
            $sd_amount_by_ga = [];
            // Data Preparation
            foreach($sd_amounts as $key => $amount) {
                $sd_amount_by_ga[$amount->ga_id]['total_deposit'] = $amount->total_deposit;
                $sd_amount_by_ga[$amount->ga_id]['paid_deposit'] = $amount->paid_deposit;
                $sd_amount_by_ga[$amount->ga_id]['balance'] = $amount->balance;
            }
            // Response
            return view('reports.consumer.sd-report.list-body', [
                'geo_areas' => $geo_areas,
                'sd_amount_by_ga' => $sd_amount_by_ga,
            ]);
        }
        return view('reports.consumer.sd-report.list');
    }

    /**
     * Get Security Deposit Details
     * @param $ga_id
     */
    public function sdDetails(Request $request)
    {
        // Get Security Deposit Details
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'created_at';
        $sortOr = ($request->get('sortOr')) ? $request->get('sortOr') : 'desc';
        $records = ($request->get('records')) ? $request->get('records') : 50;
        $sd_amounts = ConsumerScheme::with([
            'consumer:id,crn,segment_id,connection_type_id,ga_id',
            'consumer.ga:id,name',
            'consumer.segment:id,name',
            'scheme:id,name',
            'consumer.connectType:id,name'
        ])
        ->when($request->filled('geo_area') || $request->filled('segments') || $request->filled('connection_type_id'), function ($q) use ($request) {
            $q->whereHas('consumer', function ($query) use ($request) {
                // GA restriction
                if (!isAdmin() && !isSuperAdmin()) {
                    $query->whereIn('ga_id', session('user')['gas']);
                }
                if ($request->filled('geo_area')) {
                    $query->whereIn('ga_id', $request->geo_area);
                }
                if ($request->filled('connection_type_id')) {
                    $query->whereIn('connection_type_id', $request->connection_type_id);
                }
                if ($request->filled('segments')) {
                    $query->whereIn('segment_id', $request->segments);
                }
            });
        })
        ->when($request->filled('key'), function ($q) use ($request) {
            $q->where(function ($query) use ($request) {
                $query->whereAny(['total_deposit', 'paid_deposit', 'balance', 'created_at'], 'like', '%' . $request->key . '%')
                    ->orWhereHas('consumer', function ($q) use ($request) {
                    $q->where('crn', 'like', '%' . $request->key . '%');
                });
            });
        })
        ->when($request->filled('scheme'), function ($q) use($request) {
            $q->whereIn('scheme_id', $request->scheme);
        })
        ->when((!empty($request->date_from) and !empty($request->date_to)), function($q) use($request) {
            $q->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()->toDateTimeString()]);
        })
        ->orderBy($sortBy, $sortOr)->paginate($records)->withQueryString();
        // response
        if($request->ajax()) {
            return view('reports.consumer.sd-details.list-body', ['sd_amounts' => $sd_amounts]);
        }
        return view('reports.consumer.sd-details.list', ['sd_amounts' => $sd_amounts]);
    }

    /**
     * Sd Report Export
     */
    public function sdReportExport(Request $request)
    {
        return (new SDReportExport($request))->download('sd-report.xlsx');
    }
}