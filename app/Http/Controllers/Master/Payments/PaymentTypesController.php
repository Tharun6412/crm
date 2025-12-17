<?php

namespace App\Http\Controllers\Master\Payments;

use App\Http\Controllers\Controller;
use App\Models\Master\PaymentType;

class PaymentTypesController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        // Get details
        $payment_types = PaymentType::all();

        // Render output
        return view('master.payment.types.list', ['payment_types' => $payment_types]);
    }
}