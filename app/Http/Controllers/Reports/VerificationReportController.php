<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\VerifyConsumer;
use Illuminate\Http\Request;

class VerificationReportController extends Controller
{
    /**
     * Report List
     */
    public function index(Request $request)
    {
        $consumers = Consumer::where('status_id','!=',1)->count();
        $verificationCount = VerifyConsumer::selectRaw('status, COUNT(*) as total')->groupBy('status')->get()->pluck('total','status');

        $verification = VerifyConsumer::with(['consumer', 'createdBy'])
        ->when($request->filled('key'),function($q) use ($request) {
            $q->whereHas('consumer', function($q1) use ($request) {
                $q1->where('crn','like','%'.$request->key.'%');
            });
        })
        ->when($request->has('geo_area'), function ($q) use($request) {
            $q->whereHas('consumer', function($q1) use ($request){
                $q1->whereIn('ga_id', $request->geo_area);
            });
        })
        ->when($request->has('district'), function($q) use ($request){
            $q->whereHas('consumer', function($q1) use ($request){
                $q1->whereIn('district_id', $request->district);
            });
        })
        ->when($request->has('charge_area'),function($q) use ($request){
            $q->whereHas('consumer', function($q1) use ($request) {
                $q1->whereIn('ca_id', $request->charge_area);
            });
        })
        ->when($request->has('status'), function ($q) use ($request) {
            $q->whereIn('status', $request->status);
        })
        ->when($request->has('verify_steps'), function ($q) use ($request) {
            $q->whereHas('steps', function ($q1) use ($request) {
                $q1->whereIn('verify_step_id', $request->verify_steps)->where('status', 0);
            });
        })
        ->paginate(50)->withQueryString();

        if($request->ajax())
            return view('consumers.verify.list-body',['consumers' => $consumers,'verificationCount' => $verificationCount, 'verification' => $verification]);
        else
            return view('reports.verifications.list',['consumers' => $consumers,'verificationCount' => $verificationCount, 'verification' => $verification]);
    }
}
