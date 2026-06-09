<?php
namespace App\Http\Controllers\Reports;

use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Admin\User;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Master\MasterConsumerStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Employee Activity Controller
 */
class EmployeeActivityController extends Controller
{
    /**
     * Index Method
     */
    public function index(Request $request)
    {
        $request->validate([
            'geo_area' => 'required',
        ]);
        $users = User::with([
                'ga' => function ($q) use ($request) {
                    $q->whereIn('ga_id', $request->geo_area);
                },
                'cas' => function ($q) use ($request) {
                    $q->whereIn('ga_id', $request->geo_area);
                }
            ])
            ->whereHas('ga', function($q) use($request) {
                $q->whereIn('ga_id', $request->geo_area);
            })->whereHas('roles', function($q) use($request) {
                $q->whereIn('role_id', [Role::MDPE->value, Role::ACTIVATION->value, Role::GI_ENGINEER->value, Role::MARKETING->value, Role::HSE->value]);
            })
            ->when($request->has('roles'), function($q) use($request) {
                $q->whereHas('roles', function($q1) use($request) {
                    $q1->whereIn('role_id', $request->roles);
                });
            })
            ->paginate(20)->withQueryString();
            $baseRoles = [
                Role::MDPE->value,
                Role::ACTIVATION->value,
                Role::GI_ENGINEER->value,
                Role::MARKETING->value,
                Role::HSE->value,
            ];  
            $consumerStats = DB::table('cns_consumers as c')
                ->join('adm_user_ga as uga', 'uga.ga_id', '=', 'c.ga_id')
                ->join('adm_user_ca as uca', function ($join) {
                    $join->on('uca.user_id', '=', 'uga.user_id')
                        ->on('uca.ca_id', '=', 'c.ca_id');
                })
                ->join('adm_user_roles as ur', 'ur.user_id', '=', 'uga.user_id')
                ->whereIn('ur.role_id', $baseRoles)
                ->when($request->filled('roles'), function ($q) use ($request, $baseRoles) {
                    $q->whereIn('ur.role_id', array_intersect($baseRoles, $request->roles));
                })
                ->select('uga.user_id', 'c.ga_id', 'c.ca_id', 'c.status_id', DB::raw('COUNT(*) as total'))
                ->whereIn('c.ga_id', $request->geo_area)
                ->when(!empty($request->date_from) && !empty($request->date_to), function ($q) use ($request) {
                    $q->whereBetween('c.created_at', [
                        Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay(),
                        Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()
                    ]);
                })
                ->groupBy('uga.user_id', 'c.ga_id', 'c.ca_id', 'c.status_id')
                ->get();
            // Get Consumer Count
            $statsMap = [];
            foreach ($consumerStats as $row) {
                $statsMap[$row->user_id][$row->ga_id][$row->status_id] = $row->total;
            }
            $roleStatusMap = [
                Role::MARKETING->value => [EnumsConsumerStatus::PRE_REGISTER->value],
                Role::MDPE->value => [EnumsConsumerStatus::REGISTER->value],
                Role::GI_ENGINEER->value => [EnumsConsumerStatus::ACCEPT->value],
                Role::HSE->value => [EnumsConsumerStatus::EXECUTE->value],
                Role::ACTIVATION->value => [EnumsConsumerStatus::HSC->value],
            ];
        // dd($statsMap);
        $statuses = MasterConsumerStatus::whereIn('id', [EnumsConsumerStatus::REGISTER->value, EnumsConsumerStatus::EXECUTE->value, EnumsConsumerStatus::ACCEPT->value, EnumsConsumerStatus::HSC->value, EnumsConsumerStatus::ACTIVATE->value])->get();
        return view('reports.consumer.waiting-report.emp-activity', [
            'users' => $users, 
            'statsMap' => $statsMap, 
            'statuses' => $statuses,
            'roleStatusMap' => $roleStatusMap,
        ]);
    }
}