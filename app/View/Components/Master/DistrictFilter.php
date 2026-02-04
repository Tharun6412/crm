<?php

namespace App\View\Components\Master;

use App\Models\Master\District;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DistrictFilter extends Component
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
        $districts = [];
        if(request()->has('geo_area')) {
            $ga = request()->get('geo_area');
            $districts = District::whereIn('ga_id', $ga)->get();
        }
        return view('components.master.district-filter', ['districts' => $districts]);
    }
}
