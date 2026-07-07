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

class DeliveryUnitController extends Controller
{
    /**
     * Teams List Page
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
        return view('lms.delivery-units.create', ['geo_areas' => $geo_areas, 'departments' => $departments, 'charge_areas' => [], 'users' => []]);
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
        return view('lms.delivery-units.get-cas', ['charge_areas' => $cas, 'users' => $users]);
        // return response()->json(['cas' => $cas,'users' => $users]);
    }
    /**
     * Get Areas List By Charge Area
     */
    public function getCaAreas(Request $request)
    {
        $charge_areas = Ca::whereIn('id', $request->ca_id)->get();
        $areas = Area::whereIn('ca_id', $request->ca_id)->get();
        return response()->json(['areas' => $areas, 'charge_areas' => $charge_areas]);
    }
    /**
     * To store the Delivery Unit
     */
    public function store(Request $request)
    {   
        // Validation
        $request->validate([
            'name' => 'required',
            'ga_id' => 'required',
            'department_id' => 'required',
            'ca_id' => 'required',
            'du_incharge_id' => 'required',
            'area_id' => 'required|array',
            'area_id.*' => 'exists:mst_areas,id',
        ]);
        $departments = Department::whereIn('id', [
            EnumsDepartment::ACTIVATION->value,
            EnumsDepartment::HSE->value,
            EnumsDepartment::MARKETING->value,
            EnumsDepartment::GI->value,
            EnumsDepartment::MDPE->value,
            EnumsDepartment::STEEL->value,
        ])->orderBy('name', 'asc')->get();
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
            'manager_id' => $request->du_incharge_id,
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
        $delivery_unit = DeliveryUnit::find($id);
        $users = User::with(['department'])->whereHas('ga', function($q) use ($delivery_unit){
            $q->where('ga_id',$delivery_unit->ga_id);
        })->get();
        $selectedAreas = $delivery_unit->areas->pluck('id')->toArray();
        $charge_areas = Ca::where('ga_id', $delivery_unit->ga_id)->get();
        $areas = Area::whereIn('ca_id', $charge_areas->pluck('id'))->get();
        return view('lms.delivery-units.edit', [
            'delivery_unit' => $delivery_unit, 
            'users' => $users, 
            'charge_areas' => $charge_areas,
            'selectedAreas' => $selectedAreas, 
            'areas' => $areas
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
            'du_incharge_id' => 'required',
            'area_id' => 'required|array',
            'area_id.*' => 'required',
        ]);
        $delivery_unit = DeliveryUnit::find($id);
        $delivery_unit->update([
            'name' => $request->name,
            'manager_id' => $request->du_incharge_id,
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

        return response()->json([
            'success' => 'true',
            'message' => 'Status Change Successfully',
            'status' => $du->status ? 'Active' : 'Inactive',
        ]);
    }

    /**
     * Get Edit CA Areas
     */
    // public function getEditCaAreas(Request $request)
    // {
    //     $cas = Ca::where('ga_id',$request->ga_id)->get();
    //     $users = User::with(['department'])->whereHas('ga', function($q) use ($request){
    //         $q->where('ga_id',$request->ga_id);
    //     })->get();
    //     return view('lms.delivery-units.edit-cas', ['charge_areas' => $cas, 'users' => $users]);
    // }
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

