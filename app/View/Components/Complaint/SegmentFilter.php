<?php

namespace App\View\Components\Complaint;

use App\Models\Master\ComplaintSegment;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SegmentFilter extends Component
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
        $segments = ComplaintSegment::all();
        return view('components.complaint.segment-filter', ['segments' => $segments]);
    }
}
