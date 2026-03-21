<?php

namespace App\View\Components\Master;

use App\Models\Master\ComplaintCategory;
use App\Models\Master\Ga;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ComplaintCategoryFilter extends Component
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
        $categories = ComplaintCategory::whereNull('parent_id')->get();
        return view('components.master.complaint-category-filter', ['categories' => $categories]);
    }
}
