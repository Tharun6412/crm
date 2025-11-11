<?php

namespace App\View\Components\Master;

use App\Models\Master\IndustrialArea;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class IndustrialAreaFilter extends Component
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
        $industrial_areas = [];
        if(request()->has('geo_area')) {
            $industrial_areas = IndustrialArea::whereIn('ga_id', request()->geo_area)->get();
        }
        return view('components.master.industrial-area-filter', ['industrial_areas' => $industrial_areas]);
    }
}
