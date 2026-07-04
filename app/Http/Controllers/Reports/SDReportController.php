<?php

namespace App\Http\Controllers\Reports;

use App\Exports\Reports\SDReportExport;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerScheme;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Master\ConnectionType;
use App\Models\Master\Ga;
use App\Models\Master\MasterConsumerScheme;
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
            $schemes = MasterConsumerScheme::select('id', 'code','name', 'security', 'consumption', 'total_deposit', 'connection_type_id')
            ->when($request->filled('connection_type_id'), function($q) use($request) {
                $q->whereIn('connection_type_id', $request->connection_type_id);
            })->when(!$request->filled('conversion_scheme'), function($q) {
                $q->whereNotIn('id', [12,13,14,15]);
            })->when($request->filled('segments'), function($q) use($request) {
                $q->whereIn('segment_id', $request->segments);
            })->get();
            $prepaidSchemes = $schemes->where('connection_type_id', 2)->values();
            $postpaidSchemes = $schemes->where('connection_type_id', 1)->values();
            // Validation
            if(($request->filter_name == "show") AND empty($request->date_from) AND empty($request->date_to)) {
                $request->validate([
                    'date_from' => 'required|date_format:d-m-Y',
                    'date_to' => 'required|date_format:d-m-Y',
                ]);
            }
            $request->validate(['status' => 'required']);
            // Query to sum of the amounts between the dates
            $sd_amounts = Consumer::join('cns_consumer_schemes as schemes', 'schemes.consumer_id', '=', 'cns_consumers.id')
                ->when(!isAdmin() AND !isSuperAdmin(), function ($q) {
                    $q->whereIn('cns_consumers.ga_id', session('user')['gas']);
                })
                // Status filtering using EXISTS (no duplication)
                ->when($request->filled('status') || ($request->filter_name == "show" && !empty($request->date_from) && !empty($request->date_to)),
                    function ($q) use ($request) {
                        $q->whereExists(function ($sub) use ($request) {
                            $sub->selectRaw(1)->from('cns_consumer_status as status')->whereColumn('status.consumer_id', 'cns_consumers.id');
                            if ($request->filled('status')) {
                                $sub->where('status.status_id', $request->status);
                            }
                            if ($request->filter_name == "show" and !empty($request->date_from) and !empty($request->date_to)) {
                                $sub->whereBetween('status.created_at', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()]);
                            }
                        });
                    }
                )
                ->when($request->filled('connection_type_id'), function($q) use($request) {
                    $q->whereIn('cns_consumers.connection_type_id', $request->connection_type_id);
                })
                ->when($request->filled('segments'), function($q) use($request) {
                    $q->whereIn('cns_consumers.segment_id', $request->segments);
                })
                ->selectRaw('cns_consumers.ga_id,schemes.scheme_id,
                    COUNT(*) as consumer_count,
                    SUM(schemes.total_deposit) as total_deposit,
                    SUM(schemes.paid_deposit) as paid_deposit,
                    SUM(schemes.balance) as balance
                ')->groupBy('cns_consumers.ga_id', 'schemes.scheme_id')->get();
            $sd_amount_by_ga = [];
            // Data Preparation
            foreach ($sd_amounts as $amount) {
                $sd_amount_by_ga[$amount->ga_id][$amount->scheme_id] = [
                    'total_deposit' => $amount->total_deposit,
                    'paid_deposit'  => $amount->paid_deposit,
                    'balance'       => $amount->balance,
                    'count'         => $amount->consumer_count,
                ];
            }
            // Response
            return view('reports.consumer.sd-report.list-body', [
                'geo_areas' => $geo_areas,
                'sd_amount_by_ga' => $sd_amount_by_ga,
                'prepaid_schemes' => $prepaidSchemes,
                'postpaid_schemes' => $postpaidSchemes,
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
        $status = $request->filled('status') ? $request->status : 1;
        // Get Security Deposit Details
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'created_at';
        $sortOr = ($request->get('sortOr')) ? $request->get('sortOr') : 'desc';
        $records = ($request->get('records')) ? $request->get('records') : 50;
        $sd_amounts = ConsumerStatus::with([
            'consumer.scheme:id,consumer_id,scheme_id,total_deposit,paid_deposit,balance',
            'consumer.ga:id,name',
            'consumer.segment:id,name',
            'consumer.scheme.scheme:id,name',
            'consumer.connectType:id,name',
            'consumer.status:id,name'
        ])
        ->where('status_id', $status)
        ->when($request->filled('date_from') && $request->filled('date_to'), function ($q) use ($request) {
            $q->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()]);
        })
        ->whereHas('consumer', function ($q) use ($request) {
            $q->whereHas('scheme', function ($q) use ($request) {
                $q->whereNotNull('scheme_id');
            });
            // GA restriction
            if (!isAdmin() && !isSuperAdmin()) {
                $q->whereIn('ga_id', session('user')['gas']);
            }
            if ($request->filled('geo_area')) {
                $q->whereIn('ga_id', $request->geo_area);
            }
            if ($request->filled('connection_type_id')) {
                $q->whereIn('connection_type_id', $request->connection_type_id);
            }
            if ($request->filled('segments')) {
                $q->whereIn('segment_id', $request->segments);
            }
            if ($request->filled('cns_status')) {
                $q->whereIn('cns_consumers.status_id', $request->cns_status);
            }
            // Scheme filter
            if ($request->filled('scheme')) {
                $q->whereHas('scheme', function ($query) use ($request) {
                    $query->whereIn('scheme_id', $request->scheme);
                });
            }
            // Amount filter (balance inside scheme)
            if ($request->filled('amount_range')) {
                $q->whereHas('scheme', function ($query) use ($request) {
                    if ($request->amount_range == "5000+") {
                        $query->where('balance', '>', 5000);
                    } else {
                        [$min, $max] = explode('-', $request->amount_range);
                        $query->whereBetween('balance', [$min, $max]);
                    }
                });
            }
        })->orderBy($sortBy, $sortOr)->paginate($records)->withQueryString();
        $totals = [];
        // response
        if($request->ajax()) {
            return view('reports.consumer.sd-details.list-body', ['sd_amounts' => $sd_amounts, 'totals' => $totals]);
        }
        return view('reports.consumer.sd-details.list', ['sd_amounts' => $sd_amounts, 'totals' => $totals]);
    }

    public function sdDetailsCount(Request $request) 
    {
        $status = $request->filled('status') ? $request->status : 1;
        $sd_amounts = ConsumerStatus::query()->with([
            'consumer.scheme:id,consumer_id,scheme_id,total_deposit,paid_deposit,balance',
        ])
        ->where('status_id', $status)
        ->when($request->filled('date_from') && $request->filled('date_to'), function ($q) use ($request) {
            $q->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()]);
        })
        ->whereHas('consumer', function ($q) use ($request) {
            $q->whereHas('scheme', function ($q) use ($request) {
                $q->whereNotNull('scheme_id');
            });
            // GA restriction
            if (!isAdmin() && !isSuperAdmin()) {
                $q->whereIn('ga_id', session('user')['gas']);
            }
            if ($request->filled('geo_area')) {
                $q->whereIn('ga_id', $request->geo_area);
            }
            if ($request->filled('connection_type_id')) {
                $q->whereIn('connection_type_id', $request->connection_type_id);
            }
            if ($request->filled('segments')) {
                $q->whereIn('segment_id', $request->segments);
            }
            if ($request->filled('cns_status')) {
                $q->whereIn('status_id', $request->cns_status);
            }
            // Scheme filter
            if ($request->filled('scheme')) {
                $q->whereHas('scheme', function ($query) use ($request) {
                    $query->whereIn('scheme_id', $request->scheme);
                });
            }
            // Amount filter (balance inside scheme)
            if ($request->filled('amount_range')) {
                $q->whereHas('scheme', function ($query) use ($request) {
                    if ($request->amount_range == "5000+") {
                        $query->where('balance', '>', 5000);
                    } else {
                        [$min, $max] = explode('-', $request->amount_range);
                        $query->whereBetween('balance', [$min, $max]);
                    }
                });
            }
        })->get();
        $totals = [
            'total_deposit' => $sd_amounts->sum(fn($item) => optional($item->consumer?->scheme)->total_deposit ?? 0),
            'paid_deposit'  => $sd_amounts->sum(fn($item) => optional($item->consumer?->scheme)->paid_deposit ?? 0),
            'balance'       => $sd_amounts->sum(fn($item) => optional($item->consumer?->scheme)->balance ?? 0),
        ];
        return view('reports.consumer.sd-details.list-counts', compact('totals'));
    }

    /**
     * Sd Report Export
     */
    public function sdReportExport(Request $request)
    {
        // Get export by status 1.PRE_REGISTER 2.REGISTER
        if($request->status == 1) {
            return (new SDReportExport($request))->download('sd-report-tr.xlsx');
        }
        return (new SDReportExport($request))->download('sd-report-register.xlsx');
    }
}
