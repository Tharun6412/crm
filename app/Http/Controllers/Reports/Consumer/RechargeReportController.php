<?php

namespace App\Http\Controllers\Reports\Consumer;

use App\Enums\ConnectionType;
use App\Http\Controllers\Controller;
use App\Models\Master\Ga;
use App\Models\Payments\PayRecharge;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RechargeReportController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $request->filled('date_from')
            ? Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay()
            : Carbon::now()->subMonthNoOverflow()->startOfMonth()->startOfDay();

        $toDate = $request->filled('date_to')
            ? Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()
            : Carbon::now()->subMonthNoOverflow()->endOfMonth()->endOfDay();
        $gaRecharges = Ga::leftJoin('cns_consumers', 'cns_consumers.ga_id', '=', 'mst_gas.id')
            ->leftJoin('pay_recharges', function ($join) use ($fromDate, $toDate) {
                $join->on('pay_recharges.consumer_id', '=', 'cns_consumers.id')
                ->whereBetween('pay_recharges.recharge_date', [$fromDate,$toDate]);
            })
            ->selectRaw('mst_gas.id as ga_id, mst_gas.name as ga_name, COALESCE(SUM(pay_recharges.amount),0) as recharged_amount')
            ->groupBy('mst_gas.id', 'mst_gas.name')
            ->orderBy('mst_gas.id', 'asc')
            ->get();
        // Render output
        if ($request->ajax()) {
            return view('reports.payments.recharge-report.list-body', ['gaRecharges' => $gaRecharges, 'date_from' => $fromDate, 'date_to' => $toDate]);
        }
        return view('reports.payments.recharge-report.list', ['gaRecharges' => $gaRecharges, 'date_from' => $fromDate, 'date_to' => $toDate]);
    }

    /**
     * 
     * Consumer Recharges List.
     */
    public function rechargesList(Request $request)
    {
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'pay_recharges.recharge_date';
        $sortOr = ($request->get('sortOr')) ? $request->get('sortOr') : 'desc';
        $records = ($request->get('records')) ? $request->get('records') : 20;
        $consumers = PayRecharge::leftJoin('cns_consumers', 'cns_consumers.id' , '=', 'pay_recharges.consumer_id')
        ->when($request->filled('key'), function($q) use($request) {
            $q->when(function($q) use ($request){
                $q->where('cns_consumers.crn', 'like', '%'.$request->key.'%');
                $q->orWhere('cns_consumers.fname', 'like', '%'.$request->key.'%');
                $q->orWhere('cns_consumers.lname','like', '%'.$request->key.'%');
            });
        })
        ->when($request->has('segments'), function($q) use($request){
            $q->whereIn('cns_consumers.segment_id', $request->segments);
        })
        ->when($request->has('geo_area'), function ($q) use($request) {
            $q->whereIn('cns_consumers.ga_id', $request->geo_area);
        })
        ->when($request->has('district'), function ($q) use($request) {
            $q->whereIn('cns_consumers.district_id', $request->district);
        })
        ->when((!empty($request->date_from) and !empty($request->date_to)), function($q) use($request) {
            $q->whereBetween('pay_recharges.recharge_date', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()->toDateTimeString()]);
        })
        ->where('cns_consumers.connection_type_id', ConnectionType::PREPAID->value)
        ->orderBy($sortBy, $sortOr)->paginate($records)->withQueryString();

        // Render output
        if ($request->ajax()) {
            return view('reports.payments.recharge-report.recharges-list-body', ['consumers' => $consumers]);
        }
        return view('reports.payments.recharge-report.recharges-list', ['consumers' => $consumers]);
    }
}