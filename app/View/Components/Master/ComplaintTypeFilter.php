<?php

namespace App\View\Components\Master;

use App\Models\Master\ComplaintType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ComplaintTypeFilter extends Component
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
        $types = ComplaintType::all();
        return view('components.master.complaint-type-filter',['types' => $types]);
    }
}
