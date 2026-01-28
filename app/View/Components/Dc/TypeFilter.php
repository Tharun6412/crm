<?php

namespace App\View\Components\Dc;

use App\Models\DocumentCentre\DocumentTypes;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TypeFilter extends Component
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
        // Get all departments
        $doc_types = DocumentTypes::all();
        return view('components.dc.type-filter', ['doc_types' => $doc_types]);
    }
}
