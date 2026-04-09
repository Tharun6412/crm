<?php

namespace App\View\Components\Payments;

use App\Models\Master\PaymentTransactionStatus;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TransactionStatusFilter extends Component
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
        $transaction_status = PaymentTransactionStatus::all();

        return view('components.payments.transaction-status-filter', ['transaction_status' => $transaction_status]);
    }
}
