<?php

namespace App\View\Components\Payments;

use App\Models\Master\PaymentGateway;
use App\Models\Master\PaymentModule;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PaymentGatewayFilter extends Component
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
        $payment_gateways = PaymentGateway::all();

        return view('components.payments.payment-gateway-filter', ['payment_gateways' => $payment_gateways]);
    }
}
