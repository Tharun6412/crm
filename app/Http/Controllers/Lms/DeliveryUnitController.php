<?php

namespace App\Http\Controllers\Lms;

use App\Enums\ConsumerStatus;
use App\Enums\Department as EnumsDepartment;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Admin\User;
use App\Models\Lms\DeliveryUnit;
use App\Models\Lms\Team;
use App\Models\Master\Area;
use App\Models\Master\Ca;
use App\Models\Master\Department;
use App\Models\Master\Ga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveryUnitController extends Controller
{
    /**
     * Delivery Unit List Page
     */
    public function index(Request $request)
    {
        $delivery_units = DeliveryUnit::when($request->filled('key'), function($q) use ($request) {
                $q->where('name','like','%'.$request->key.'%');
            })
            ->when((!isAdmin() AND !isSuperAdmin() AND !isFullAccess()), function($q) {
                $q->whereIn('ga_id',session('user')['gas']);
            })
            ->orderByDesc('created_at')
            ->paginate(20)->withQueryString();
        if($request->ajax()) {
            return view('lms.delivery-units.list-body', ['delivery_units' => $delivery_units]);
        }else {
            return view('lms.delivery-units.list', ['delivery_units' => $delivery_units]);
        }
    }

    /**
     * Create Delivery Unit
     */
    public function create(Request $request)
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
        // Reesponse
        return view('lms.delivery-units.create', [
            'geo_areas' => $geo_areas, 
            'departments' => $departments, 
            'charge_areas' => [], 
            'users' => [],
            'areas' => [],
        ]);
    }
    /**
     * Get Areas List By Charge Area
     */
    public function getCaAreas(Request $request)
    {
        $users = User::with(['department'])->whereHas('ga', function($q) use ($request){
            $q->where('ga_id',$request->ga_id);
        })->orderBy('first_name', 'asc')->get();
        $charge_areas = Ca::select('id', 'name', 'ga_id')->where('ga_id', $request->ga_id)->get();
        // Already assigned area IDs
        $assigned_areas = DB::table('lms_du_areas')
            ->join('lms_delivery_units', 'lms_delivery_units.id', '=', 'lms_du_areas.du_id')
            ->where('lms_delivery_units.ga_id', $request->ga_id)
            ->where('lms_delivery_units.dept_id', $request->department_id)
            ->pluck('lms_du_areas.area_id')->toArray();
        $areas = Area::select('id', 'name', 'ca_id')->whereIn('ca_id', $charge_areas->pluck('id'))->get();
        // Response
        return view('lms.delivery-units.get-cas', [
            'areas' => $areas, 
            'charge_areas' => $charge_areas, 
            'users' => $users, 
            'assigned_areas' => $assigned_areas,
        ]);
    }
    /**
     * To store the Delivery Unit
     */
    public function store(Request $request)
    {   
        // Validation
        $request->validate([
            'name' => 'required',
            'department_id' => 'required',
            'ga_id' => 'required',
            'delivery_manager_id' => 'required',
            'area_id' => 'required|array',
            'area_id.*' => 'exists:mst_areas,id',
        ]);
        switch($request->department_id) {
            case EnumsDepartment::STEEL->value:
                $responsible_status = ConsumerStatus::REGISTER->value;
                $action_status = ConsumerStatus::ACCEPT->value;
                break;
            case EnumsDepartment::MDPE->value:
                $responsible_status = ConsumerStatus::REGISTER->value;
                $action_status = ConsumerStatus::ACCEPT->value;
                break;
            case EnumsDepartment::MARKETING->value:
                $responsible_status = ConsumerStatus::REGISTER->value;
                $action_status = ConsumerStatus::ACCEPT->value;
                break;
            case EnumsDepartment::GI->value:
                $responsible_status = ConsumerStatus::ACCEPT->value;
                $action_status = ConsumerStatus::EXECUTE->value;
                break;
            case EnumsDepartment::HSE->value:
                $responsible_status = ConsumerStatus::EXECUTE->value;
                $action_status = ConsumerStatus::HSC->value;
                break;
            case EnumsDepartment::ACTIVATION->value:
                $responsible_status = ConsumerStatus::HSC->value;
                $action_status = ConsumerStatus::ACTIVATE->value;
                break;
            default:
                $responsible_status = $action_status = '';
        }
        $delivery_unit = DeliveryUnit::create([
            'name' => $request->name,
            'manager_id' => $request->delivery_manager_id,
            'ga_id' => $request->ga_id,
            'dept_id' => $request->department_id,
            'responsible_status_id' => $responsible_status,
            'action_status_id' => $action_status,
            'status' => 1,
            'created_by' => Auth::id(),
        ]);
        $areas = [];
        foreach ($request->input('area_id', []) as $areaId) {
            $areas[$areaId] = [
                'dept_id' => $request->department_id,
            ];
        }
        $delivery_unit->areas()->sync($areas);
        // Response
        return response()->json(['success' => 'Delivery Unit created successfully']);
    }

    /**
     * Edit Delivery Unit 
     * @param $du_id
     */
    public function edit(Request $request, $id)
    {
        $delivery_unit = DeliveryUnit::with(['ga:id,name', 'department:id,name', 'areas'])->find($id);
        $users = User::with(['department'])->whereHas('ga', function($q) use ($delivery_unit){
            $q->where('ga_id',$delivery_unit->ga_id);
        })->get();
        // Selected Areas for the selected GA
        $selected_areas = $delivery_unit->areas->pluck('id')->toArray();
        $charge_areas = Ca::where('ga_id', $delivery_unit->ga_id)->get();
        $areas = Area::whereIn('ca_id', $charge_areas->pluck('id'))->get();
        // Disable Areas if selected for other DU
        $disabled_areas = DeliveryUnit::where('ga_id', $delivery_unit->ga_id)->where('dept_id', $delivery_unit->dept_id)
            ->where('id', '!=', $delivery_unit->id)
            ->with('areas:id')->get()
            ->flatMap->areas
            ->pluck('id')->unique()->toArray();
        return view('lms.delivery-units.edit', [
            'delivery_unit' => $delivery_unit, 
            'users' => $users, 
            'charge_areas' => $charge_areas,
            'selected_areas' => $selected_areas, 
            'areas' => $areas,
            'disabled_areas' => $disabled_areas,
        ]);
    }

    /**
     * To Update Delivery Unit Details
     */
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'name' => 'required',
            'delivery_manager_id' => 'required',
            'area_id' => 'required|array',
            'area_id.*' => 'required',
        ]);
        $delivery_unit = DeliveryUnit::find($id);
        $delivery_unit->update([
            'name' => $request->name,
            'manager_id' => $request->delivery_manager_id,
            'updated_by' => Auth::id(),
        ]);

        $areas = [];
        foreach ($request->input('area_id', []) as $areaId) {
            $areas[$areaId] = [
                'dept_id' => $delivery_unit->dept_id,
            ];
        }
        $delivery_unit->areas()->sync($areas);
        // Response
        return response()->json(['success' => 'Delivery Unit updated Successfully']);
    }

    /**
     * status 
     */
    public function toggleStatus($id)
    {
        $du = DeliveryUnit::findOrFail($id);
        $du->status = !$du->status;
        $du->save();
        // Response
        return response()->json([
            'success' => 'true',
            'message' => 'Status Change Successfully',
            'status' => $du->status ? 'Active' : 'Inactive',
        ]);
    }
    /**
     * Show Details of Delivery Unit
     * @param $du_id
     */
    public function show(Request $request, $id)
    {
        $delivery_unit = DeliveryUnit::find($id);
        $du_teams = Team::where('du_id', $id)->get();
        return view('lms.delivery-units.show', ['delivery_unit' => $delivery_unit, 'du_teams' => $du_teams]);
    }
}

