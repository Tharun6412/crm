<?php

namespace App\View\Components\Master;

use App\Models\Master\BillCollectionCenter;
use App\Models\Master\Ga;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BillCollection extends Component
{
   /**
     * Ga Id
     * @var int $gaId
     */
    public $gaId;

    /**
     * Create a new component instance.
     * @param int $gaId
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
        // Get all geo areas
        $centers = BillCollectionCenter::where('ga_id', $this->gaId)->get();
        return view('components.master.bill-collection-centers', ['centers' => $centers]);
    }
}
