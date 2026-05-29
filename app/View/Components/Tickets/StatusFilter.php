<?php

namespace App\View\Components\Tickets;

use App\Models\Master\TicketStatus;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatusFilter extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $ticket_status = TicketStatus::all();
        return view('components.tickets.status-filter',['ticket_status' => $ticket_status]);
    }
}
