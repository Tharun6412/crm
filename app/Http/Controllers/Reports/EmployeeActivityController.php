<?php
namespace App\Http\Controllers\Reports;

use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\Department;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Lms\Team;
use App\Models\Admin\User;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Consumer\TeamConsumer;
use App\Models\Master\MasterConsumerStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                $q->whereIn('role_id', [Role::ACTIVATION->value, Role::GI_ENGINEER->value, Role::MARKETING->value, Role::HSE->value]);
            })
            ->when($request->has('roles'), function($q) use($request) {
                $q->whereHas('roles', function($q1) use($request) {
                    $q1->whereIn('role_id', $request->roles);
                });
            })
            ->get();
            $baseRoles = [
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
                ->select('uga.user_id', 'c.ga_id', 'c.status_id', DB::raw('COUNT(DISTINCT c.id) as total'))
                ->whereIn('c.ga_id', $request->geo_area)
                ->when(!empty($request->date_from) && !empty($request->date_to), function ($q) use ($request) {
                    $q->whereBetween('c.created_at', [
                        Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay(),
                        Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()
                    ]);
                })
                ->groupBy('uga.user_id', 'c.ga_id', 'c.status_id')
                ->get();
            // Get Consumer Count
            $consumer_counts = [];
            foreach ($consumerStats as $row) {
                $consumer_counts[$row->user_id][$row->ga_id][$row->status_id] = ($consumer_counts[$row->user_id][$row->ga_id][$row->status_id] ?? 0) + $row->total;
            }
            // Mapping Roles to Consumer Status
            $roleStatusMap = [
                Role::MARKETING->value => [EnumsConsumerStatus::PRE_REGISTER->value, EnumsConsumerStatus::REGISTER->value],
                Role::GI_ENGINEER->value => [EnumsConsumerStatus::ACCEPT->value],
                Role::HSE->value => [EnumsConsumerStatus::EXECUTE->value],
                Role::ACTIVATION->value => [EnumsConsumerStatus::HSC->value],
            ];
        // List of Consumers waiting for statuss
        $statuses = MasterConsumerStatus::whereIn('id', [EnumsConsumerStatus::REGISTER->value, EnumsConsumerStatus::EXECUTE->value, EnumsConsumerStatus::ACCEPT->value, EnumsConsumerStatus::HSC->value, EnumsConsumerStatus::ACTIVATE->value])->get();
        return view('reports.consumer.waiting-report.emp-activity', [
            'users' => $users, 
            'consumer_counts' => $consumer_counts, 
            'statuses' => $statuses,
            'roleStatusMap' => $roleStatusMap,
        ]);
    }

    /**
     * To get Assigned and Assigned List 
     * Teams with Assigned List
     * @param $user_id
     */
    public function getUserAssignedTeams(Request $request)
    {
        // Map Status ID
        switch($request->cns_status) {
            case 1:
                $status = 2;
                $dept_id = Department::MARKETING->value;
                $status_id = 1;
                break;
            case 2:
                $status = 3;
                $dept_id = Department::MARKETING->value;
                $status_id = 2;
                break;
            case 3:
                $status = 4;
                $dept_id = Department::GI->value;
                $status_id = 3;
                break;
            case 4:
                $status = 5;
                $dept_id = Department::HSE->value;
                $status_id = 4;
                break;
            case 5:
                $status = 6;
                $dept_id = Department::ACTIVATION->value;
                $status_id = 5;
                break;
            default:
                $status = $status_id = $dept_id =  ''; break; 
        }
        // fetch User Details and get Role IDs
        $user = User::find($request->user_id);
        $caIds = $user?->cas->pluck('id') ?? collect();
        // Fetch Teams List
        $teams = Team::whereIn('ga_id', $request->ga_id)->whereHas('cas', function($q) use($caIds) {
            $q->whereIn('mst_cas.id', $caIds);
        })->where('department_id', $dept_id)->get();
        // List of Assigned Consumers by User
        $assigned_consumers = TeamConsumer::select('team_id', DB::raw('COUNT(id) as team_count'))->whereIn('team_id', $teams->pluck('id'))->where('status_id', $status)->where('status', 0)->groupBy('team_id')->get()->pluck('team_count', 'team_id');
        $total = $request->total;
        $unassigned_list = $total - $assigned_consumers->sum();
        return view('reports.consumer.waiting-report.user-assigned-teams', [
            'teams' => $teams,
            'assigned_consumers' => $assigned_consumers,
            'unassigned' => $unassigned_list ?? 0,
            'user' => $user,
            'status_id' => $status_id,
        ]);
    }
}