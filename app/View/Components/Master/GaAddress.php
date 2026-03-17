<?php

namespace App\View\Components\Master;

use App\Models\Master\BillAddress;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class GaAddress extends Component
{
    /**
     * Ga Id
     */
    public $gaId;

    /**
     * Create a new component instance.
     */
    public function __construct($gaId)
    {
        $this->gaId = $gaId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // Get address
        $address = BillAddress::where('ga_id', $this->gaId)->first();

        // Render output
        return view('components.master.ga-address', ['address' => $address]);
    }
}
