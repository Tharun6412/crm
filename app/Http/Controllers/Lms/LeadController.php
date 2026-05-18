<?php

namespace App\Http\Controllers\Lms;

use App\Enums\LeadStatus;
use App\Http\Controllers\Controller;
use App\Models\Lms\Lead;
use App\Models\Lms\LeadActivities;
use App\Models\Lms\LeadChannel;
use App\Models\Lms\LeadStatus as LmsLeadStatus;
use App\Models\Master\Area;
use App\Models\Master\Ca;
use App\Models\Master\District;
use App\Models\Master\Ga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    /**
     * lead list Page
     */
    public function index(Request $request)
    {
        $status = LmsLeadStatus::whereNull('parent_id')->get();        
        $leads = Lead::with([
            'ga:id,name',
            'district:id,name',
            'ca:id,name',
            'area:id,name',
            'leadChannel:id,name',
            'status.parent'
        ])
        //search by Lead code
        ->when($request->filled('key'),function($q) use ($request) {
            $q->where('code','like','%'. $request->key .'%');
        })
        //filter by geo_area
        ->when($request->has('geo_area'),function($q) use ($request) {
            $q->whereIn('ga_id',$request->geo_area);
        })
        //filter by district
        ->when($request->has('district'),function($q) use ($request){
            $q->whereIn('district_id',$request->district);
        })
        //filter by charge area
        ->when($request->has('ca'),function($q) use($request) {
            $q->whereIn('ca_id',$request->ca);
        })
        //parent status filter
        ->when($request->has('status_id'),function($q) use ($request){
            $q->whereHas('status.parent',function($q1) use ($request){
                $q1->whereIn('id',$request->status_id);
            });
        })
        //child status filter
        ->when($request->has('child_status_id'),function($q) use ($request){
            $q->whereIn('status_id',$request->child_status_id);
        })
        ->orderByDesc('created_at')
        ->paginate(50)
        ->withQueryString();

        if($request->ajax())
            return view('lms.list-body',['leads' => $leads,'status' => $status]);
        else
            return view('lms.list',['leads' => $leads,'status' => $status]);
    }
    /**
     * create a lead page
     */
    public function create()
    {
        //get all geo_areas
        $geo_areas = Ga::all();
        $channels = LeadChannel::all();
        return view('lms.create',['geo_areas' => $geo_areas,'channels' => $channels]);
    }
    /**
     * store the lead
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'mobile' => 'required|numeric',
            'address_line1' => 'required',
            'address_line2' => 'required',
            'ga_id' => 'required',
            'district_id' => 'required',
            'ca_id' => 'required',
            'area_id' => 'required',
            'notes' => 'required',
            'lead_channel_id' => 'required',
        ]);

        $add_lead = Lead::create([
            'code' => $request->code,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'owner_ship' => $request->owner_ship,
            'lpg_service' => $request->lpg_service,
            'ga_id' => $request->ga_id,
            'district_id' => $request->district_id,
            'ca_id' => $request->ca_id,
            'area_id' => $request->area_id,
            'lead_channel_id' => $request->lead_channel_id,
            'status_id' => LeadStatus::CREATED->value,
            'created_by' => Auth::id(),
        ]);
        //generate Lead Code
        $lead_code = 'L' . str_pad($add_lead->ca->code, 2, '0', STR_PAD_LEFT). str_pad($add_lead->id, 4, '0', STR_PAD_LEFT);
        //update the code
        $add_lead->update([
            'code' => $lead_code,
        ]);
        LeadActivities::create([
            'lead_id' => $add_lead->id,
            'lead_status_id' => $add_lead->status_id,
            'lead_channel_id' => $request->lead_channel_id,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);

        return response()->json(['success' => 'Lms Created Successfully']);
    }
    /**
     * lead show
     */
    public function show($id)
    {
        $leads = Lead::findOrFail($id);
        return view('lms.show',['leads' => $leads]);
    }
    /**
     * change the lead status
     */
    public function statusChange($id)
    {
        $lead = Lead::with('status')->find($id);
        $parent_status = LmsLeadStatus::whereNull('parent_id')->get();
        $child_status = LmsLeadStatus::where('parent_id',$lead->status?->parent_id)->get();
        $channels = LeadChannel::all();

        return view('lms.status', [
            'lead' => $lead,
            'parent_status' => $parent_status,
            'child_status' => $child_status,
            'channels' => $channels,
        ]);
    }
    /**
     * get substatus based on status
     */
    public function getChildStatus(Request $request)
    {
        $child_status = LmsLeadStatus::where('parent_id',$request->parent_id)->get();
        return response()->json([ 'child_status' => $child_status]);
    }
    /**
     * update the lead status
     */
    public function statusUpdate(Request $request,$id)
    {
        $request->validate([
            'notes' => 'required',
        ]);

        $lead = Lead::find($id);
        $lead->update([
            'status_id' => $request->status_id,
            'lead_channel_id' => $request->lead_channel_id,
        ]);
        LeadActivities::create([
            'lead_id' => $lead->id,
            'lead_status_id' => $lead->status_id,
            'lead_channel_id' => $request->lead_channel_id,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        return response()->json(['success' => "Lead Updated Successfully"]);
    }
    
}
