<?php

namespace App\View\Components\Admin;

use App\Models\Pmc\ChargeArea;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ChargeAreaFilter extends Component
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
        $charge_areas = ChargeArea::all();
        // Pass the charge areas to the view
        if(request()->has('geo_area')) {
            $geo_area = request()->get('geo_area');
            $charge_areas = ChargeArea::whereIn('ga_id', $geo_area)->get();
            return view('components.admin.charge-area-filter', ['charge_areas' => $charge_areas]);
        } 
    }
}
