<?php
namespace App\Http\Controllers\Reports;

use App\Enums\ConsumerStatus;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Lms\DeliveryUnit;
use App\Models\Master\MasterConsumerStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryUnitReportController extends Controller
{
    // Index method
    public function index(Request $request)
    {
        // Validation
        $request->validate([
            'geo_area' => 'required',
        ]);
        // Get GA Delivery Units
        $delivery_units = DeliveryUnit::with(['areas'])
            ->when($request->has('departments'), function($q) use($request) {
                $q->whereIn('dept_id', $request->departments);
            })
            ->whereIn('ga_id', $request->geo_area)->orderBy('ga_id', 'desc')->get();
        // Get Consumers count Based on Areas
        $consumer_counts = DB::table('cns_consumers')
            ->join('lms_du_areas', 'cns_consumers.area_id', '=', 'lms_du_areas.area_id')
            ->join('lms_delivery_units', 'lms_du_areas.du_id', '=', 'lms_delivery_units.id')
            ->select(
                'lms_delivery_units.id as du_id',
                'lms_delivery_units.responsible_status_id',
                DB::raw('COUNT(cns_consumers.id) as total')
            )
            ->whereIn('cns_consumers.ga_id', $request->geo_area)
            ->whereColumn(
                'cns_consumers.status_id',
                'lms_delivery_units.responsible_status_id'
            )
            ->groupBy(
                'lms_delivery_units.id',
                'lms_delivery_units.responsible_status_id'
            )
            ->get();
        $count = [];
        foreach ($consumer_counts as $row) {
            $count[$row->du_id] = [
                'status_id' => $row->responsible_status_id,
                'total' => $row->total,
            ];
        }
        // dd($count);
        $status_list = MasterConsumerStatus::whereIn('id', [ConsumerStatus::ACTIVATE->value, ConsumerStatus::ACCEPT->value, ConsumerStatus::EXECUTE->value, ConsumerStatus::HSC->value])->get();
        // Response
        return view('reports.delivery-units.list', [
            'delivery_units' => $delivery_units,
            'count' => $count,
            'status_list' => $status_list
        ]);
    }
}