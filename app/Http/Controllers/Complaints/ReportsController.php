<?php
namespace App\Http\Controllers\Complaints;

use App\Http\Controllers\Controller;
use App\Models\Master\ComplaintCategory;
use App\Models\Master\Ga;
use App\Models\Master\MasterComplaintStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Default loading of complaints tab view.
     */
    public function complaints(Request $request)
    {
        return view('complaints.reports.list');
    }
    /**
     *  GA based report for complaints as per the status.
     * 
     */
    public function gaReport(Request $request)
    {
        // between dates initialisation.
        $from = $request->date_from ? Carbon::parse($request->date_from)->startOfDay() : now()->startOfMonth();
        $to = $request->date_to ? Carbon::parse($request->date_to)->endOfDay() : now()->endOfDay();
        // getting all complaint statuses
        $statuses = MasterComplaintStatus::all();
        // preparing the select statement dynamically from statuses.
        $select = [
            'mst_gas.id',
            'mst_gas.name',
            DB::raw('COUNT(cmp_complaints.id) as total')
        ];
        foreach ($statuses as $status) {
            $select[] = DB::raw("
                SUM(CASE 
                    WHEN cmp_complaints.status_id = {$status->id} 
                    THEN 1 ELSE 0 
                END) as status_{$status->id}
            ");
        }

        // fetching the GA based report.
        $gaComplaints = Ga::leftJoin('cmp_complaints', function ($join) use ($from, $to) {
            $join->on('cmp_complaints.ga_id', '=', 'mst_gas.id')
            ->whereBetween('cmp_complaints.created_at', [$from, $to]);
        })
        ->select(
            $select
        )
        ->groupBy('mst_gas.id', 'mst_gas.name', 'cmp_complaints.status_id')
        ->get();

        // rendering output.
        return view('complaints.reports.ga.list-body', [
            'gaComplaints' => $gaComplaints, 
            'date_from' => $from, 
            'date_to' => $to, 
            'statuses' => $statuses,
        ]); 
    }

    /**
     * Main category based report as per the complaint status.
     */
    public function categoryReport(Request $request)
    {
        // between dates initialisation.
        $from = $request->date_from ? Carbon::parse($request->date_from)->startOfDay() : now()->startOfMonth();
        $to = $request->date_to ? Carbon::parse($request->date_to)->endOfDay() : now()->endOfDay();

        // fetching all GAs for GA filter.
        $gas = Ga::all();
        //  fetching statuses for the display loop
        $statuses = MasterComplaintStatus::all();
        // preparing the select statement for statuses.
        $select = [
            'mst_cmp_categories.id',
            'mst_cmp_categories.name',
            DB::raw('COUNT(cmp_complaints.id) as total')
        ];
        foreach ($statuses as $status) {
            $select[] = DB::raw("
                SUM(CASE 
                    WHEN cmp_complaints.status_id = {$status->id} 
                    THEN 1 ELSE 0 
                END) as status_{$status->id}
            ");
        }
        // main query.
        $categoryComplaints = ComplaintCategory::leftJoin('mst_cmp_categories as sub_cat', function($join){
            $join->on('sub_cat.parent_id', '=', 'mst_cmp_categories.id');
        })
        ->leftJoin('cmp_complaints', function($join) use($from, $to){
            $join->on('cmp_complaints.category_id', '=', 'sub_cat.id')
            ->whereBetween('cmp_complaints.created_at',[$from, $to]);
        })
        ->whereNull('mst_cmp_categories.parent_id')
        ->select(
            $select
        )
        ->groupBy('mst_cmp_categories.id', 'mst_cmp_categories.name')
        ->get();

        // rendering output.
        return view('complaints.reports.category.list-body', [
            'categoryComplaints' => $categoryComplaints, 
            'date_from' => $from, 
            'date_to' => $to, 
            'statuses' => $statuses,
            'gas' => $gas,
        ]);
    }

    /**
     * GA based deviation report of complaints.
     *
     */
    public function deviationReport(Request $request)
    {
        $from = $request->date_from ? Carbon::parse($request->date_from)->startOfDay() : now()->startOfMonth();
        $to = $request->date_to ? Carbon::parse($request->date_to)->endOfDay() : now()->endOfDay();

        $deviationReports = Ga::leftJoin('cmp_complaints', function ($join) use ($from, $to) {
                $join->on('cmp_complaints.ga_id', '=', 'mst_gas.id')
                    ->whereBetween('cmp_complaints.created_at', [$from, $to]);
            })
            ->selectRaw(
                'mst_gas.id,
                mst_gas.name,
                SUM(
                    CASE 
                        WHEN (
                            CASE 
                                WHEN cmp_complaints.closed_at IS NOT NULL 
                                    THEN DATEDIFF(cmp_complaints.closed_at, cmp_complaints.estimated_closed_at)
                                ELSE DATEDIFF(CURDATE(), cmp_complaints.estimated_closed_at)
                            END
                        ) > 0 
                    THEN 1 ELSE 0 END
                ) as deviated_count,
                SUM(
                    CASE 
                        WHEN (
                            CASE 
                                WHEN cmp_complaints.closed_at IS NOT NULL 
                                    THEN DATEDIFF(cmp_complaints.closed_at, cmp_complaints.estimated_closed_at)
                                ELSE DATEDIFF(CURDATE(), cmp_complaints.estimated_closed_at)
                            END
                        ) <= 0 
                    THEN 1 ELSE 0 END
                ) as non_deviated_count,
                COUNT(cmp_complaints.id) as total'
            )
            ->groupBy('mst_gas.id', 'mst_gas.name')
            ->get();

            // rendering output.
        return view('complaints.reports.deviation.list-body', [
            'deviationReports' => $deviationReports, 
            'date_from' => $from, 
            'date_to' => $to,
        ]);
    }
}