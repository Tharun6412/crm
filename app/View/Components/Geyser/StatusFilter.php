<?php

namespace App\View\Components\Geyser;

use App\Models\Master\GeyserStatus;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatusFilter extends Component
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
        $geyser_status = GeyserStatus::all();
        return view('components.geyser.status-filter',['geyser_status' => $geyser_status]);
    }
}
