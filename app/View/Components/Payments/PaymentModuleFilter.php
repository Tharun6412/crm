<?php

namespace App\View\Components\Payments;

use App\Models\Master\PaymentModule;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PaymentModuleFilter extends Component
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
        // Get all payment modules
        $paymentModules = PaymentModule::all();

        return view('components.payments.payment-module-filter', ['payment_modules' => $paymentModules]);
    }
}
