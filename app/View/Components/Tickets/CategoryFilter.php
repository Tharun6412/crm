<?php

namespace App\View\Components\Tickets;

use App\Models\Master\TicketCategory;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CategoryFilter extends Component
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
        $category_filter = TicketCategory::all();
        return view('components.tickets.category-filter',['category_filter' => $category_filter]);
    }
}
