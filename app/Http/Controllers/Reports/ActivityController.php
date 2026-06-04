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

        $user_roles = User::select('users.id', 'users.first_name', 'users.last_name')
            ->whereHas('roles', function ($q) {
                $q->whereIn('adm_roles.id', [
                    EnumsRole::MDPE->value,
                    EnumsRole::HSE->value,
                    EnumsRole::ACTIVATION->value,
                    EnumsRole::STEEL->value,
                    EnumsRole::GI_ENGINEER->value,
                ]);
            })->get();

        $userIds = $user_roles->pluck('id');
        
        $users = ConsumerStatus::join('users','users.id','=','cns_consumer_status.created_by')
            ->select('cns_consumer_status.created_by','cns_consumer_status.status_id',DB::raw('COUNT(cns_consumer_status.status_id) as count'))
            ->whereIn('cns_consumer_status.created_by', $userIds)
            ->whereBetween('cns_consumer_status.created_at', [$from, $to])
            ->when(($request->has('geo_area') AND !empty($request->geo_area)), function($q) use($request) {
                $q->whereHas('consumer', function($q1) use($request) {
                    $q1->whereIn('ga_id', $request->geo_area);
                    });
                })
                ->groupBy('cns_consumer_status.created_by','cns_consumer_status.status_id')->get();

        $geo_areas = Ga::select('id','name')->whereIn('id',$request->geo_area)->first();

        $users_data = [];
        $users_sum = [];

        foreach ($users as $user) {
            $users_data[$user->created_by][$user->status_id] = $user->count;
            $users_sum[$user->status_id] = isset($users_sum[$user->status_id]) ? $users_sum[$user->status_id] + $user->count : $user->count;
        }

        return view('reports.consumer.activity.list', [
            'user_roles' => $user_roles,
            'users_data' => $users_data,
            'users_sum'  => $users_sum,
            'geo_areas' => $geo_areas,
        ]);
    }
}
