<?php

namespace App\View\Components\Complaint;

use App\Models\Master\MasterComplaintStatus;
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
        // Get consumer status
        $cmp_status = MasterComplaintStatus::all();
        return view('components.complaint.status-filter', ['cmp_status' => $cmp_status]);
    }
}
