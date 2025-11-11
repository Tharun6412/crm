<?php

namespace App\View\Components\Master;

use App\Models\Master\FuelType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CurrentFuelFilter extends Component
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
        $fuel_list = FuelType::all();
        return view('components.master.current-fuel-filter', ['fuel_list' => $fuel_list]);
    }
}
