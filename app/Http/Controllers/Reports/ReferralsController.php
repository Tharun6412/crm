<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Consumer\Referral;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReferralsController extends Controller
{
    public function index(Request $request) 
    {
        // Get referral requests
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'created_at';
        $sortOr = ($request->get('sortOr')) ? $request->get('sortOr') : 'desc';
        $records = ($request->get('records')) ? $request->get('records') : 50;

        $ref_requests = Referral::with([
                'consumer:id,t_crn,crn,fname,lname,phone,status_id,ga_id,segment_id,connection_type_id',
                'consumer.consumerData:consumer_id,reference_code,referrer_consumer_id',
                'consumer.ga:id,name',
                'consumer.status:id,name',  
            ])
            ->when($request->filled('key'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->whereAny(['name','phone'], 'like', '%' . $request->key . '%')
                    ->orWhereHas('consumer', function ($q1) use ($request) {
                        $q1->whereAny(['t_crn', 'crn', 'fname', 'lname', 'email', 'phone'], 'like', '%' . $request->key . '%');
                    });
                });
            })
            ->when($request->has('segments'), function ($q) use($request) { 
                $q->whereHas('consumer', function($q1) use($request) {
                    $q1->whereIn('segment_id', $request->segments);
                });
            })
            ->when($request->filled('connection_type_id'), function ($q) use($request) {
                $q->whereHas('consumer', function($q1) use($request) {
                    $q1->where('connection_type_id', $request->connection_type_id);
                });
            })
            ->when($request->has('geo_area'), function ($q) use($request) {
                $q->whereHas('consumer', function($q1) use($request) {
                    $q1->whereIn('ga_id', $request->geo_area);
                });
            })
            ->when($request->has('district'), function ($q) use($request) {
                $q->whereHas('consumer', function($q1) use($request) {
                    $q1->whereIn('district_id', $request->district);
                });
            })
            ->when($request->has('cns_status'), function ($q) use($request) {
                $q->whereHas('consumer', function($q1) use($request) {
                    $q1->whereIn('status_id', $request->cns_status);
                });
            })
            ->when((!empty($request->date_from) and !empty($request->date_to)), function($q) use($request) {
                $q->whereBetween('created_at', [
                    Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay()->toDateTimeString(),
                    Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()->toDateTimeString()
                ]);
            })
            ->orderBy($sortBy, $sortOr)->paginate($records)->withQueryString();
        
            // dd($requests);
        if($request->ajax()) {
            return view('reports.referrals.list-body', ['ref_requests' => $ref_requests]);
        }
        else {
            return view('reports.referrals.list',['ref_requests' => $ref_requests]);
        }
    }

    public function show($id)
    {
        $referral = Referral::find($id);

        return view('reports.referrals.show',['referral' => $referral]);
    }
}