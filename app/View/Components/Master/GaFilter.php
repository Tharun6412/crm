<?php

namespace App\View\Components\Master;

use App\Models\Master\Ga;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class GaFilter extends Component
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
        $geo_areas = Ga::all();
        return view('components.master.ga-filter', ['geo_areas' => $geo_areas]);
    }
}
