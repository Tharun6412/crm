<?php

namespace App\Http\Controllers\Reports;

use App\Enums\Role as EnumsRole;
use App\Http\Controllers\Controller;
use App\Models\Admin\Role;
use App\Models\Admin\User;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Master\Ga;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function activity(Request $request)
    {
    $request->validate([
        'geo_area' => 'required',
        'conv_date_from' => 'required',
        'conv_date_to' => 'required',
    ]);

    $from = Carbon::parse($request->conv_date_from)->startOfDay();
    $to = Carbon::parse($request->conv_date_to)->endOfDay();

    $emp_kpi = ConsumerStatus::with(['createdBy'])
        ->selectRaw('COUNT(id) as consumer_count, status_id, created_by')
         ->whereNotNull('created_by')
        ->whereBetween('cns_consumer_status.created_at', [$from, $to])
        ->when($request->filled('geo_area'), function ($q) use ($request) {
            $q->whereHas('consumer', function ($q1) use ($request) {
                $q1->whereIn('ga_id', $request->geo_area);
            });
        })
        ->groupBy('created_by', 'status_id')
        ->get(); 
        $geo_areas = Ga::select('id','name')->whereIn('id',$request->geo_area)->first();

    $users_data = [];
    $users_sum = [];
    $user_roles = [];
    foreach ($emp_kpi as $row) {
        $user_roles[$row->created_by] = $row->createdBy->name ?? '';
        $users_data[$row->created_by][$row->status_id] = $row->consumer_count;
        $users_sum[$row->status_id] = ($users_sum[$row->status_id] ?? 0) + $row->consumer_count;
    }
    return view('reports.consumer.activity.list', [
        'user_roles' => $user_roles,
        'users_data' => $users_data,
        'users_sum'  => $users_sum,
        'geo_areas' => $geo_areas,
    ]);
}
}
