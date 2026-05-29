<?php

namespace App\Http\Controllers\Tickets;

use App\Http\Controllers\Controller;
use App\Models\Tickets\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Ticket dashboard
     */
    public function index()
    {
        $tickets = Ticket::select('status_id',DB::raw('COUNT(id) as status_count'))->groupby('status_id')->get();
        return view('tickets.dashboard.dashboard',['tickets' => $tickets]);
    }
}
