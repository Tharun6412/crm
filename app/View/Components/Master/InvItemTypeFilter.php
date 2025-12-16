<?php

namespace App\View\Components\Master;

use App\Models\Master\BillInvoiceItemType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InvItemTypeFilter extends Component
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
        // Get all geo areas
        $item_types = BillInvoiceItemType::all();
        return view('components.master.item-type-filter', ['item_types' => $item_types]);
    }
}
