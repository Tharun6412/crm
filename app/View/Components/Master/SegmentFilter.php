<?php

namespace App\View\Components\Master;

use App\Models\Master\Segment;
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
        // Get all geo areas
        $segments = Segment::all();
        return view('components.master.segment-filter', ['segments' => $segments]);
    }
}
