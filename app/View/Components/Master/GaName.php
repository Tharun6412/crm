<?php

namespace App\View\Components\Master;

use App\Models\Master\Ga;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class GaName extends Component
{
    /**
     * Array of geo area ids
     */
    public $ga_ids;

    /**
     * Create a new component instance.
     */
    public function __construct($gaIds)
    {
        $this->ga_ids = $gaIds;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        // Get all geo areas
        $geo_areas = Ga::whereIn('id', $this->ga_ids)->get()->pluck('name');
        return view('components.master.ga-name', ['geo_areas' => $geo_areas]);
    }
}
