<?php

namespace App\View\Components\Consumer;

use App\Models\Master\MasterConsumerStatus;
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
        // Get consumer status
        $cns_status = MasterConsumerStatus::all();
        return view('components.consumer.status-filter', ['cns_status' => $cns_status]);
    }
}
