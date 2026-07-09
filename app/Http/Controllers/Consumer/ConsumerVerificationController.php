<?php

namespace App\Http\Controllers\Consumer;

use App\Exports\Consumers\ConsumerVerificationExport;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\VerifyConsumer;
use App\Models\Consumer\VerifyConsumerStep;
use App\Models\Master\ConsumerVerificationStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsumerVerificationController extends Controller
{
    /**
     * List of verifications
     */
    // public function index(Request $request)
    // {
    //     $verification = VerifyConsumer::with(['consumer', 'createdBy'])
    //     ->when($request->filled('key'),function($q) use ($request) {
    //         $q->whereHas('consumer', function($q1) use ($request) {
    //             $q1->where('crn','like','%'.$request->key.'%');
    //         });
    //     })
    //     ->when($request->has('geo_area'), function ($q) use($request) {
    //         $q->whereHas('consumer', function($q1) use ($request){
    //             $q1->whereIn('ga_id', $request->geo_area);
    //         });
    //     })
    //     ->when($request->has('district'), function($q) use ($request){
    //         $q->whereHas('consumer', function($q1) use ($request){
    //             $q1->whereIn('district_id', $request->district);
    //         });
    //     })
    //     ->when($request->has('charge_area'),function($q) use ($request){
    //         $q->whereHas('consumer', function($q1) use ($request) {
    //             $q1->whereIn('ca_id', $request->charge_area);
    //         });
    //     })
    //     ->when($request->has('status'), function ($q) use ($request) {
    //         $q->whereIn('status', $request->status);
    //     })
    //     ->paginate(50)->withQueryString();
    //     if($request->ajax())
    //         return view('consumers.verify.list-body',['verification' => $verification]);
    //     else
    //         return view('consumers.verify.list',['verification' => $verification]);
    // }
    /**
     * Verify Consumers Details
     */
    public function edit($id)
    {
       $consumer = Consumer::with('verification')->findOrFail($id);
       $steps = ConsumerVerificationStep::all();
       return view('consumers.verify.edit',['consumer' => $consumer,'steps' => $steps]); 
    }
    /**
     * Update Verification details
     */
    public function update(Request $request,$id)
    {
        $rules = [];
        for($i=1;$i<6;$i++){
            $rules["status.$i"] = 'required';

            if (($request->status[$i] ?? null) == 0) {
                $rules["remarks.$i"] = 'required';
            }
        }
        $request->validate($rules);

        $consumer = Consumer::findOrFail($id);

        $verifyStatus = in_array(0, $request->status) ? 0 : 1;
       
        $verification = VerifyConsumer::create([
            'consumer_id' => $id,
            'status' => $verifyStatus,
            'remarks' => $request->issues,
            'created_by' => Auth::id(),
        ]);
        foreach ($request->status as $stepId => $status){
            VerifyConsumerStep::create([
                'verify_consumer_id' => $verification->id,
                'verify_step_id' => $stepId,
                'status' => $status,
                'remarks' => $request->remarks[$stepId] ?? '',
            ]);
        }
        return response()->json(['success' => 'Consumer Verification Completed Successfully']);
    }
    /**
     * Edit Verify status
     */
    public function editStatus($id)
    {
        $verification = VerifyConsumer::findOrFail($id);
        return view('consumers.verify.edit-status',['verification' => $verification ]);
    }
    /**
     * Update Verify Status
     */
    public function updateStatus(Request $request,$id)
    {
        $request->validate([
            'status' => 'required',
            'updated_remarks' => 'required',
        ]);
        $verification = VerifyConsumer::findOrFail($id);
        $verification->update([
            'status' => $request->status,
            'updated_remarks' => $request->updated_remarks,
            'updated_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'Verification details Updated Successfully']);
    }
    /**
     * Show details
     */
    public function show($id)
    {
        $verification = VerifyConsumer::with('steps')->findOrFail($id);
        return view('consumers.verify.show',['verification' => $verification]);
    }
    /**
     * Export
     */
    public function verificationExport(Request $request)
    {
        return (new ConsumerVerificationExport($request))->download('verification.csv');
    }
}
