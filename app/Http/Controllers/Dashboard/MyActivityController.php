<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\Role as EnumsRole;
use App\Http\Controllers\Controller;
use App\Models\Admin\Role;
use App\Models\Admin\Team;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Consumer\TeamConsumer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MyActivityController extends Controller
{
    /**
     * Index
     * 
     * Dashboard
     */
    public function index(Request $request)
    {
        // Consumer Waitin(g List for the Responsible User
        $roles = Role::whereIn('id', [EnumsRole::MDPE->value, EnumsRole::GI_ENGINEER->value, EnumsRole::HSE->value, EnumsRole::ACTIVATION->value, EnumsRole::MARKETING->value])
            ->whereIn('id', Auth::user()->roles->pluck('id'))->pluck('name', 'id');
        $consumers_count = Consumer::select('status_id', DB::raw('COUNT(id) as consumer_count'))->whereIn('ga_id', Auth::user()->ga->pluck('id'))->whereIn('ca_id', Auth::user()->cas->pluck('id'))->groupBy('status_id')->get()->pluck('consumer_count', 'status_id');
        $teams = Team::whereHas('users', function($q) use($request) {
            $q->where('user_id', Auth::id());
        })->get();
        $team_keys = $teams->pluck('id')->toArray();
        // Pending Consumers List
        $consumer_team_count = TeamConsumer::select('team_id', DB::raw('COUNT(id) as team_count'))->where('status', 0)->whereIn('team_id', $team_keys)->groupBy('team_id')->get()->pluck('team_count', 'team_id');
        // Completed consumers List
        $completed_consumers = ConsumerStatus::select('status_id', DB::raw('COUNT(id) as total_count'))->whereIn('status_id', [EnumsConsumerStatus::REGISTER->value, EnumsConsumerStatus::ACCEPT->value, EnumsConsumerStatus::HSC->value, EnumsConsumerStatus::EXECUTE->value, EnumsConsumerStatus::ACTIVATE->value])->where('created_by', Auth::id())->groupBy('status_id')->get()->pluck('total_count', 'status_id');

        // print "<pre>"; print_r($completed_consumers); 
        // Render output
        return view('dashboard.my-activity', [
            'roles' => $roles,
            'consumers_count' => $consumers_count,
            'teams' => $teams,
            'consumer_team_count' => $consumer_team_count,
            'completed_consumers' => $completed_consumers,
        ]);
    }
}