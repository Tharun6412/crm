<?php
namespace App\Http\Controllers\Reports;

use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Admin\User;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Employee Activity Controller
 */
class EmployeeActivityController extends Controller
{
    /**
     * Index Method
     */
    public function index(Request $request)
    {
        $request->validate([
            'geo_area' => 'required',
        ]);
        $users = User::with([
                'ga' => function ($q) use ($request) {
                    $q->whereIn('ga_id', $request->geo_area);
                },
                'cas' => function ($q) use ($request) {
                    $q->whereIn('ga_id', $request->geo_area);
                }
            ])
            ->whereHas('ga', function($q) use($request) {
                $q->whereIn('ga_id', $request->geo_area);
            })->whereHas('roles', function($q) use($request) {
                $q->whereIn('role_id', [Role::MDPE->value, Role::ACTIVATION->value, Role::GI_ENGINEER->value, Role::MARKETING->value, Role::HSE->value]);
            })
            ->when($request->has('roles'), function($q) use($request) {
                $q->whereHas('roles', function($q1) use($request) {
                    $q1->whereIn('role_id', $request->roles);
                });
            })
            ->withCount(['consumers as count' => function($q) use($request) {
                $q->whereIn('ga_id', $request->geo_area)
                  ->whereIn('status_id', [EnumsConsumerStatus::REGISTER->value, EnumsConsumerStatus::EXECUTE->value, EnumsConsumerStatus::HSC->value, EnumsConsumerStatus::ACCEPT->value])
                  ->when((!empty($request->date_from) and !empty($request->date_to)), function($q1) use($request) {
                        $q1->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()->toDateTimeString()]);
                });
            }])
            ->orderBy('count', 'desc')
            ->paginate(20)->withQueryString();
        // dd($consumers_count);
        return view('reports.consumer.waiting-report.emp-activity', ['users' => $users]);
    }
}