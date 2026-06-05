<?php

namespace App\View\Components\Master;

use App\Models\Master\SubArea;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SubAreaFilter extends Component
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
        $subareas = SubArea::all();
        return view('components.master.sub-area-filter',['subareas' => $subareas]);
    }
}
