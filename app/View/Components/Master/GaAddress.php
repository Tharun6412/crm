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
     * @var int $gaId
     */
    public $gaId;

    /**
     * Display
     * @var int $display
     */
    public $display;

    /**
     * Create a new component instance.
     * @param int $gaId
     * @param int $display
     */
    public function __construct($gaId, $display = 0)
    {
        $this->gaId = $gaId;
        $this->display = $display;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // Get address
        $address = BillAddress::where('ga_id', $this->gaId)->first();

        // Render output
        return view('components.master.ga-address', ['address' => $address, 'display' => $this->display]);
    }
}
