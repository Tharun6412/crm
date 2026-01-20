<?php

namespace App\Http\Controllers\Master\Payments;

use App\Http\Controllers\Controller;
use App\Models\Master\Ga;
use App\Models\Master\PaymentGateway;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        // Get
        $paymentGateways = PaymentGateway::all();

        // Render output
        return view('master.payment.payment-gateways.list', [
            'payment_gateways' => $paymentGateways,
        ]);
    }

    /**
     * Show
     */
    public function show($id)
    {
        // Get payment gateway details
        $gateway = PaymentGateway::find($id);

        // Render output
        return view('master.payment.payment-gateways.show', ['gateway' => $gateway]);
    }

    /**
     * Edit
     */
    public function edit($id)
    {
        // Get details
        $gateway = PaymentGateway::find($id);
        $gas = Ga::all();

        // Render output
        return view('master.payment.payment-gateways.edit', [
            'gateway' => $gateway,
            'gas' => $gas,
        ]);
    }

    /**
     * Update
     */
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'gateway' => 'required',
        ]);

        // Update

        // Response
        return response()->json(['success' => 'Updated successfully!']);
    }
}