<?php 
namespace App\View\Components\Master;

use App\Models\Master\BillInvoiceType;
use Closure;
use Illuminate\View\Component;
use Illuminate\View\View;

class InvoiceTypeFilter extends Component
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
        // Get all Invoice Types.
        $invoice_types = BillInvoiceType::all();
        return view('components.master.invoice-type-filter', ['invoice_types' => $invoice_types]);
    }
}