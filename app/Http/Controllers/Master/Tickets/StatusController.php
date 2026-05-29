<?php

namespace App\Http\Controllers\Master\Tickets;

use App\Http\Controllers\Controller;
use App\Models\Master\TicketStatus;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * List of status
     */
    public function index()
    {
        $status = TicketStatus::all();
        return view('master.tickets.status',['status' => $status]);
    }
}
