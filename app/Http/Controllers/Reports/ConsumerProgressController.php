<?php
namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;

use App\Models\Admin\Team;
use App\Models\Consumer\TeamConsumer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Consumer Activity Controller
 */
class ConsumerProgressController extends Controller
{
    /**
     * Index Method
     */
    public function index(Request $request)
    {
        if(empty($request->geo_area) OR empty($request->status)) {
            abort(403, 'No details found');
        }
        // Get Consumer Teams Assigned And Completed List
        $consumers = TeamConsumer::whereHas('team', function($q) use($request) {
                $q->whereIn('ga_id', $request->geo_area);
            })
            ->whereHas('consumer', function($q) use($request) {
                $q->when(($request->has('charge_area') and !empty($request->charge_area)), function($q1) use($request) {
                    $q1->whereIn('ca_id', $request->charge_area);
                });
                $q->when(($request->has('area') and !empty($request->area)), function($q1) use($request) {
                    $q1->whereIn('area_id', $request->area);
                });
            })
            ->when(($request->has('team_id') AND !empty($request->team_id)), function($q) use($request) {
                $q->where('team_id', $request->team_id);
            })
            ->when(($request->has('updated_by') AND !empty($request->updated_by)), function($q) use($request) {
                $q->where('updated_by', $request->updated_by);
            })
            ->when(($request->has('status') AND !empty($request->status)), function($q) use($request) {
                $q->whereIn('status', $request->status);
            })
            ->when(($request->has('team_ids') and !empty($request->team_ids)), function($q) use($request) {
                $q->whereIn('team_id', $request->team_ids);
            })
            ->when((!empty($request->date_from) and !empty($request->date_to)), function($q) use($request) {
                $q->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()]);
            })
            ->paginate(50)->withQueryString();
        // Response
        if($request->ajax()) {
            return view('reports.consumer.waiting-report.consumer-team-progress.list-body', [
                'consumers' => $consumers
            ]);    
        }else {
            return view('reports.consumer.waiting-report.consumer-team-progress.list', [
                'consumers' => $consumers
            ]);
        }
    }
}