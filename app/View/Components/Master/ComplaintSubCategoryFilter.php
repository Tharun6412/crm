<?php

namespace App\View\Components\Master;

use App\Models\Master\ComplaintCategory;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ComplaintSubCategoryFilter extends Component
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
        // Get all complain sub categories
        $sub_categories = [];
        if(request()->has('category')) {
            $category = request()->category;
            $categories = ComplaintCategory::whereIn('id', $category)->get();
        }
        return view('components.master.complaint-subcategory-filter', ['categories' => $categories]);
    }
}
