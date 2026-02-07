<?php

namespace App\Http\Controllers\Master\Payments;

use App\Http\Controllers\Controller;
use App\Models\Master\Ga;
use App\Models\Master\PaymentGateway;
use App\Models\Master\PaymentGatewayDetails;
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
        $geo_areas = Ga::all();

        // Render output
        return view('master.payment.payment-gateways.edit', [
            'gateway' => $gateway,
            'geo_areas' => $geo_areas,
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
            'status' => 'required',
        ]);

        // Update gateway
        $update_gateway = PaymentGateway::where('id', $id)->update([
            'gateway' => $request->gateway,
            'is_active' => $request->status,

        ]);
        // Update gateway details
        $pg_details = [];
        foreach($request->sub_merchant as $ga_id => $value) {
            $pg_details[] = [
                'payment_gateway_id' => $id,
                'ga_id' => $ga_id,
                'sub_merchant_id' => $value,
            ];
        }
        if(sizeof($pg_details) > 0)
            $update_gateway_details = PaymentGatewayDetails::upsert($pg_details, ['payment_gateway_id', 'ga_id'], ['sub_merchant_id']);

        // Response
        return response()->json(['success' => 'Updated successfully!']);
    }
}