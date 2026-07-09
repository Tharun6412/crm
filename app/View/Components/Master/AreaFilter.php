<?php

namespace App\View\Components\Master;

use App\Models\Master\Area;
use App\Models\Master\Ca;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AreaFilter extends Component
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
        // Pass the charge areas to the view
        if(request()->has('charge_area')) {
            $charge_area = request()->get('charge_area');
            $areas = Area::whereIn('ca_id', $charge_area)->orderBy('name', 'asc')->get();
            return view('components.master.area-filter', ['areas' => $areas]);
        } 
    }
}
