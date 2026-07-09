<?php

namespace App\Http\Controllers\Lms;

use App\Enums\Department as EnumsDepartment;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Lms\Team;
use App\Models\Admin\User;
use App\Models\Lms\DeliveryUnit;
use App\Models\Master\Area;
use App\Models\Master\Ca;
use App\Models\Master\Department;
use App\Models\Master\Ga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    /**
     * Teams List Page
     */
    public function index(Request $request)
    {
        $teams = Team::with([
            'ga:id,name',
            'departments:id,name',
        ])
        ->withCount(['users as users_count'])
        ->when($request->filled('key'), function($q) use ($request) {
            $q->where('name','like','%'.$request->key.'%');
        })
        ->when((!isAdmin() AND !isSuperAdmin() AND !isFullAccess()), function($q) {
            $q->whereIn('ga_id',session('user')['gas']);
        })
        ->when($request->has('geo_area'), function($q) use ($request) {
            $q->whereIn('ga_id',$request->geo_area);
        })
        ->when($request->has('departments'), function($q) use ($request) {
            $q->whereIn('department_id',$request->departments);
        })
        ->orderByDesc('created_at')
        ->paginate(20)->withQueryString();

        if($request->ajax())
            return view('lms.teams.list-body',['teams' => $teams]);
        else
            return view('lms.teams.list',['teams' => $teams]);
    }
    /**
     * create Team
     */
    public function create()
    {
        $geo_areas = Ga::when((!isAdmin() AND !isSuperAdmin() AND !isFullAccess()), function($q) {
            $q->whereIn('id',session('user')['gas']);
        })->get();
        $departments = Department::whereIn('id', [
            EnumsDepartment::ACTIVATION->value,
            EnumsDepartment::HSE->value,
            EnumsDepartment::MARKETING->value,
            EnumsDepartment::GI->value,
            EnumsDepartment::MDPE->value,
            EnumsDepartment::STEEL->value,
        ])->orderBy('name', 'asc')->get();
        
        return view('lms.teams.create',['geo_areas' => $geo_areas,'departments' => $departments]);
    }
    /**
     * Store Team
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'department_id' => 'required',
            'ga_id' => 'required',
            'responsible_user_id' => 'required',
            'du_id' => 'required',
        ]);

        $team = Team::create([
            'name' => $request->name,
            'ga_id' => $request->ga_id,
            'department_id' => $request->department_id,
            'du_id' => $request->du_id,
            'status' => 1,
            'responsible_user_id' => $request->responsible_user_id,
            'created_by' => Auth::id(),
        ]);

        $team->areas()->sync($request->area_id ?? []);
        return response()->json(['success' => 'Team Created Successfully']);

    }
    /**
     * get ca based on the ga
     */
    public function gaCas(Request $request)
    {
        $cas = Ca::where('ga_id',$request->ga_id)->get();
        $users = User::with(['department'])->whereHas('ga', function($q) use ($request){
            $q->where('ga_id',$request->ga_id);
        })->get();
        return response()->json(['cas' => $cas,'users' => $users]);
    }

    /**
     * get Delivery Units based on the Department and GA
     */
    public function getDeliveryUnits(Request $request)
    {
        $delivery_units = DeliveryUnit::where('ga_id', $request->ga_id)->where('dept_id', $request->dept_id)->where('status', 1)->get();
        return response()->json(['delivery_units' => $delivery_units]);
    }

    /**
     * get Delivery Units based on the Department and GA
     */
    public function getDeliveryUnitAreas(Request $request)
    {
        $du = DeliveryUnit::find($request->du_id);
        $area_ids = $du->areas->pluck('id')->toArray();
        // Assigned Areas
        // Areas already assigned to teams
        $allocated_area_ids = DB::table('lms_team_areas')->whereIn('area_id', $area_ids)->pluck('area_id');
        $areas = Area::select('id', 'name', 'ca_id')->whereIn('id', $area_ids)->get();
        $cas = $areas->pluck('ca_id')->unique()->toArray();
        $charge_areas = Ca::select('id', 'name')->whereIn('id', $cas)->get();
        return response()->json(['charge_areas' => $charge_areas,'areas' => $areas, 'allocated_areas' => $allocated_area_ids]);
    }
    /**
     * team edit
     */
    public function edit($id)
    {
        $team = Team::when((!isAdmin() AND !isSuperAdmin() AND !isFullAccess()), function($q) {
            $q->whereIn('ga_id', session('user')['gas']);
        })->with(['cas','ga','departments','responsibleUser'])->findOrFail($id);
        $cas = Ca::where('ga_id', $team->ga_id)->get();
        $role_id = [];
        switch($team->department_id) {
            case EnumsDepartment::MARKETING->value:
                $role_id[] = Role::MARKETING->value;break;
            case EnumsDepartment::MDPE->value:
                $role_id[] = Role::MDPE->value;break;
            case EnumsDepartment::STEEL->value:
                $role_id[] = Role::STEEL->value;break;
            case EnumsDepartment::GI->value:
                $role_id[] = Role::GI_ENGINEER->value;break;
            case EnumsDepartment::HSE->value:
                $role_id[] = Role::HSE->value; break;
            case EnumsDepartment::ACTIVATION->value:
                $role_id[] = Role::ACTIVATION->value;break;
            default: $role_id = []; break;
        }
        // Get Users
        $users = User::whereHas('ga', function($q) use ($team){
            $q->where('ga_id',$team->ga_id);
        })
        ->whereHas('roles', function($q) use($role_id) {
                $q->whereIn('adm_roles.id', array_unique($role_id));
            })
        ->orderBy('first_name', 'asc')
        ->get();
        // Delivery Unit
        $delivery_units = DeliveryUnit::where('ga_id', $team->ga_id)->where('dept_id', $team->department_id)->get();
        $delivery_unit = DeliveryUnit::find($team->du_id);
        $area_ids = $delivery_unit?->areas?->pluck('id')->toArray() ?? [];
        $areas = Area::select('id', 'name', 'ca_id')->whereIn('id', $area_ids)->get();
        $ca_ids = $areas->pluck('ca_id')->unique()->toArray();
        $cas = Ca::select('id', 'name')->whereIn('id', $ca_ids)->get();
        // Disabled Areas
        $disabled_areas = Team::where('id', '!=', $team->id)->where('ga_id', $team->ga_id)->where('department_id', $team->department_id)
            ->with('areas:id')
            ->get()->pluck('areas')
            ->flatten()
            ->pluck('id')
            ->unique()
            ->toArray();
        // Response
        return view('lms.teams.edit',[
            'team' => $team,
            'cas' => $cas,
            'users' => $users, 
            'areas' => $areas, 
            'delivery_units' => $delivery_units,
            'disabled_areas' => $disabled_areas,
        ]);
    }
    /**
     * team update
     */
    public function update(Request $request, $id)
    {
        // validation
        $request->validate([
            'name' => 'required',
            'area_id' => 'required',
        ]);

        $team = Team::findOrFail($id);

        $team->update([
            'name' => $request->name,
            'ga_id' => $request->ga_id,
            'department_id' => $request->department_id,
            'du_id' => $request->du_id,
            'responsible_user_id' => $request->responsible_user_id,
        ]);
        // Areas Mapping
        $team->areas()->sync($request->area_id ?? []);
        // Response
        return response()->json([
            'success' => 'Team Updated Successfully'
        ]);
    }
    /**
     * charges areas show page
     */
    public function show($id)
    {
        $team = Team::with(['ga','departments','users.roles','responsibleUser', 'deliveryUnit', 'deliveryUnit.areas'])->findOrFail($id);
        return view('lms.teams.show',['team' => $team ]);
    }
    /**
     * status 
     */
    public function toggleStatus($id)
    {
        $team = Team::findOrFail($id);
        $team->status = !$team->status;
        $team->save();
        // Response
        return response()->json([
            'success' => 'true',
            'message' => 'Status Change Successfully',
            'status' => $team->status ? 'Active' : 'Inactive',
        ]);
    }

}

