<?php
namespace App\Http\Controllers\Reports\Consumer;

use App\Enums\ConsumerStatus;
use App\Exports\Reports\Consumers\ConsumerExport;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus as ConsumerConsumerStatus;
use App\Models\Master\Ga;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Consumer Ageing Report Controller.
 */
class ConsumerAgeingReport extends Controller
{
     /**
     * GA wise counts of the consumers who are still not activated.
     */
    public function index(Request $request)
    { 
        $regQuery = ConsumerConsumerStatus::select('consumer_id',DB::raw('MIN(created_at) as register_date'))->where('status_id',ConsumerStatus::REGISTER->value)->groupBy('consumer_id');
        $actQuery = ConsumerConsumerStatus::select('consumer_id',DB::raw('MIN(created_at) as activation_date'))->where('status_id',ConsumerStatus::ACTIVATE->value)->groupBy('consumer_id');
        
        $gasAging = Ga::leftJoin('cns_consumers', function ($join) use ($request) {
                $join->on('mst_gas.id', '=', 'cns_consumers.ga_id');
                if ($request->filled('segments')) {
                    $join->whereIn('cns_consumers.segment_id', $request->segments);
                }
                if ($request->filled('connection_type_id')) {
                    $join->where('cns_consumers.connection_type_id', $request->connection_type_id);
                }
            })
            ->leftJoinSub($regQuery, 'reg', function ($join) {
                $join->on('cns_consumers.id', '=', 'reg.consumer_id');
            })
            ->leftJoinSub($actQuery, 'act', function ($join) {
                $join->on('cns_consumers.id', '=', 'act.consumer_id');
            })
            ->selectRaw("
                mst_gas.id as ga_id,
                mst_gas.name as ga_name,

                /* Inactive */
                SUM(CASE 
                    WHEN cns_consumers.status_id NOT IN (". ConsumerStatus::ACTIVATE->value .",". ConsumerStatus::REJECT->value .",". ConsumerStatus::TD->value ."," . ConsumerStatus::PD->value . ")
                    AND DATEDIFF(NOW(), reg.register_date) < 90
                    THEN 1 ELSE 0 END) as inactive_upto_90,
                
                SUM(CASE 
                    WHEN cns_consumers.status_id NOT IN (". ConsumerStatus::ACTIVATE->value .",". ConsumerStatus::REJECT->value .",". ConsumerStatus::TD->value ."," . ConsumerStatus::PD->value . ")
                    AND DATEDIFF(NOW(), reg.register_date) BETWEEN 91 AND 120
                    THEN 1 ELSE 0 END) as inactive_90_120,

                SUM(CASE 
                    WHEN cns_consumers.status_id NOT IN (". ConsumerStatus::ACTIVATE->value .",". ConsumerStatus::REJECT->value .",". ConsumerStatus::TD->value ."," . ConsumerStatus::PD->value . ")
                    AND DATEDIFF(NOW(), reg.register_date) > 120
                    THEN 1 ELSE 0 END) as inactive_gt_120,

                /* Active */
                SUM(CASE 
                    WHEN cns_consumers.status_id = ". ConsumerStatus::ACTIVATE->value ."
                    AND DATEDIFF(act.activation_date, reg.register_date) <= 90
                    THEN 1 ELSE 0 END) as active_upto_90,

                SUM(CASE 
                    WHEN cns_consumers.status_id = ". ConsumerStatus::ACTIVATE->value ."
                    AND DATEDIFF(act.activation_date, reg.register_date) BETWEEN 91 AND 120
                    THEN 1 ELSE 0 END) as active_90_120,

                SUM(CASE 
                    WHEN cns_consumers.status_id = ". ConsumerStatus::ACTIVATE->value ."
                    AND DATEDIFF(act.activation_date, reg.register_date) > 120
                    THEN 1 ELSE 0 END) as active_gt_120
            ")
            ->groupBy('mst_gas.id', 'mst_gas.name')
            ->orderBy('mst_gas.id')
            ->get();
        $not_active = $gasAging->map(function ($row) {
            return (object)[
                'ga_id'   => $row->ga_id,
                'ga_name' => $row->ga_name,
                'inactive_upto_90' => $row->inactive_upto_90,
                'inactive_90_120' => $row->inactive_90_120,
                'inactive_gt_120'  => $row->inactive_gt_120,
            ];
        });

        $active = $gasAging->map(function ($row) {
            return (object)[
                'ga_id'   => $row->ga_id,
                'ga_name' => $row->ga_name,
                'active_upto_90' => $row->active_upto_90,
                'active_90_120' => $row->active_90_120,
                'active_gt_120'  => $row->active_gt_120,
            ];
        });
        // Render output
        if($request->ajax()) {
            return view('reports.consumer.consumer-aging-report.list-body', ['not_active' => $not_active, 'active' => $active]);
        }
        return view('reports.consumer.consumer-aging-report.list', ['not_active' => $not_active, 'active' => $active]);
    }
    // public function index(Request $request)
    // {
    //     $statusDates = DB::table('cns_consumer_status')
    //         ->selectRaw("
    //             consumer_id,
    //             MIN(CASE WHEN status_id = " . ConsumerStatus::REGISTER->value . " THEN created_at END) as register_date,
    //             MIN(CASE WHEN status_id = " . ConsumerStatus::ACTIVATE->value . " THEN created_at END) as activation_date
    //         ")
    //         ->whereIn('status_id', [
    //             ConsumerStatus::REGISTER->value,
    //             ConsumerStatus::ACTIVATE->value
    //         ])
    //         ->groupBy('consumer_id');
    //     $today = now()->toDateString();
    //     $gasAging = DB::table('mst_gas as g')
    //         ->leftJoin('cns_consumers as c', function ($join) use ($request) {
    //             $join->on('g.id', '=', 'c.ga_id');
    //             if ($request->filled('segments')) {
    //                 $join->whereIn('c.segment_id', $request->segments);
    //             }
    //         })
    //         ->leftJoinSub($statusDates, 'sd', function ($join) {
    //             $join->on('c.id', '=', 'sd.consumer_id');
    //         })
    //         ->selectRaw("
    //             g.id as ga_id,
    //             g.name as ga_name,
    //             /* Inactive */
    //             SUM(IF(
    //                 c.id IS NOT NULL
    //                 AND c.status_id NOT IN (" . ConsumerStatus::ACTIVATE->value . "," . ConsumerStatus::REJECT->value . "," . ConsumerStatus::TD->value . "," . ConsumerStatus::PD->value . ")
    //                 AND DATEDIFF('$today', sd.register_date) < 90, 1, 0)) as inactive_upto_90,
    //             SUM(IF(
    //                 c.id IS NOT NULL
    //                 AND c.status_id NOT IN (" . ConsumerStatus::ACTIVATE->value . "," . ConsumerStatus::REJECT->value . "," . ConsumerStatus::TD->value . "," . ConsumerStatus::PD->value . ")
    //                 AND DATEDIFF('$today', sd.register_date) BETWEEN 91 AND 120,1,0)) as inactive_90_120,
    //             SUM(IF(
    //                 c.id IS NOT NULL
    //                 AND c.status_id NOT IN (" . ConsumerStatus::ACTIVATE->value . "," . ConsumerStatus::REJECT->value . "," . ConsumerStatus::TD->value . "," . ConsumerStatus::PD->value . ")
    //                 AND DATEDIFF('$today', sd.register_date) > 120,1,0)) as inactive_gt_120, 
    //             /* Active */
    //             SUM(IF(
    //                 c.status_id = " . ConsumerStatus::ACTIVATE->value . "
    //                 AND DATEDIFF(sd.activation_date, sd.register_date) <= 90,1,0)) as active_upto_90,

    //             SUM(IF(
    //                 c.status_id = " . ConsumerStatus::ACTIVATE->value . "
    //                 AND DATEDIFF(sd.activation_date, sd.register_date) BETWEEN 91 AND 120,1,0)) as active_90_120,
    //             SUM(IF(
    //                 c.status_id = " . ConsumerStatus::ACTIVATE->value . "
    //                 AND DATEDIFF(sd.activation_date, sd.register_date) > 120,1,0)) as active_gt_120
    //         ")
    //         ->groupBy('g.id', 'g.name')
    //         ->orderBy('g.id')
    //         ->get();
    //     $not_active = $gasAging->map(function ($row) {
    //         return (object)[
    //             'ga_id'   => $row->ga_id,
    //             'ga_name' => $row->ga_name,
    //             'inactive_upto_90' => $row->inactive_upto_90,
    //             'inactive_90_120' => $row->inactive_90_120,
    //             'inactive_gt_120'  => $row->inactive_gt_120,
    //         ];
    //     });

    //     $active = $gasAging->map(function ($row) {
    //         return (object)[
    //             'ga_id'   => $row->ga_id,
    //             'ga_name' => $row->ga_name,
    //             'active_upto_90' => $row->active_upto_90,
    //             'active_90_120' => $row->active_90_120,
    //             'active_gt_120'  => $row->active_gt_120,
    //         ];
    //     });
    //     // Render output
    //     if($request->ajax()) {
    //         return view('reports.consumer.consumer-aging-report.list-body', ['not_active' => $not_active, 'active' => $active]);
    //     }
    //     return view('reports.consumer.consumer-aging-report.list', ['not_active' => $not_active, 'active' => $active]);
    // }

    public function consumersList(Request $request)
    {
        // Get consumers
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'created_at';
        $sortOr = ($request->get('sortOr')) ? $request->get('sortOr') : 'desc';
        $records = ($request->get('records')) ? $request->get('records') : 50;
        $consumers = Consumer::when((!isAdmin() AND !isSuperAdmin()), function ($q) {
                $q->whereIn('ga_id', session('user')['gas']);
            })
            ->when($request->filled('key'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->whereAny(['crn', 'fname', 'lname', 'email', 'phone'], 'like', '%' . $request->key . '%')
                    ->orWhereHas('meter', function ($q1) use ($request) {
                        $q1->whereAny(['meter_no', 'meter_serial_no'], 'like', '%' . $request->key . '%');
                    });
                });
            })
            ->when($request->has('segments'), function ($q) use($request) {
                $q->whereIn('segment_id', $request->segments);
            })
            ->when($request->filled('connection_type_id'), function ($q) use($request) {
                $q->where('connection_type_id', $request->connection_type_id);
            })
            ->when($request->has('geo_area'), function ($q) use($request) {
                $q->whereIn('ga_id', $request->geo_area);
            })
            ->when($request->has('district'), function ($q) use($request) {
                $q->whereIn('district_id', $request->district);
            })
            ->when($request->has('charge_area'), function ($q) use($request) {
                $q->whereIn('ca_id', $request->charge_area);
            })
            ->when($request->has('area'), function ($q) use($request) {
                $q->whereIn('area_id', $request->area);
            })
            ->when($request->has('scheme'), function ($q) use($request) {
                $q->where(function($query) use($request) {
                    $query->whereHas('scheme', function($q1) use($request) {
                        $q1->whereIn('scheme_id', $request->scheme);
                    });
                });
            })
            ->when($request->has('cns_status'), function ($q) use($request) {
                $q->whereIn('status_id', $request->cns_status);
            })
            ->when((!empty($request->date_from) and !empty($request->date_to)), function($q) use($request) {
                $q->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()->toDateTimeString()]);
            })
            ->when($request->filled('range') && $request->filled('type'), function ($q) use ($request) {
                $regSub = ConsumerConsumerStatus::select( 'consumer_id', DB::raw('MIN(created_at) as register_date'))
                    ->where('status_id', ConsumerStatus::REGISTER->value)
                    ->groupBy('consumer_id');
                $q->leftJoinSub($regSub, 'reg', function ($join) {
                    $join->on('cns_consumers.id', '=', 'reg.consumer_id');
                });
                if ($request->type == 1) {
                    switch ($request->range) {
                        case '90-':
                            $q->whereRaw('DATEDIFF(NOW(), reg.register_date) <= 90');
                            break;
                        case '90-120':
                            $q->whereRaw('DATEDIFF(NOW(), reg.register_date) BETWEEN 91 AND 120');
                            break;
                        case '120+':
                            $q->whereRaw('DATEDIFF(NOW(), reg.register_date) > 120');
                            break;
                    }
                }
                if ($request->type == 2) {
                    $actSub = ConsumerConsumerStatus::select('consumer_id',DB::raw('MIN(created_at) as activation_date'))
                        ->where('status_id', ConsumerStatus::ACTIVATE->value)
                        ->groupBy('consumer_id');
                    $q->leftJoinSub($actSub, 'act', function ($join) {
                        $join->on('cns_consumers.id', '=', 'act.consumer_id');
                    });
                    switch ($request->range) {
                        case '90-':
                            $q->whereRaw('DATEDIFF(act.activation_date, reg.register_date) <= 90');
                            break;
                        case '90-120':
                            $q->whereRaw('DATEDIFF(act.activation_date, reg.register_date) BETWEEN 91 AND 120');
                            break;
                        case '120+':
                            $q->whereRaw('DATEDIFF(act.activation_date, reg.register_date) > 120');
                            break;
                    }
                }
            })
            ->orderBy($sortBy, $sortOr)->paginate($records)->withQueryString();
        
        // Render output
        if($request->ajax()) {
            // dd($request->all());
            return view('reports.consumer.consumer-aging-report.consumer-list-body', ['consumers' => $consumers]);
        }
        else {
            return view('reports.consumer.consumer-aging-report.consumer-list', ['consumers' => $consumers]);
        }
    }

    /**
     * Consumers Export
     */
    public function consumerExport(Request $request)
    {
        return (new ConsumerExport($request))->download('consumers.xlsx');
    }
}