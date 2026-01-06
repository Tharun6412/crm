<?php

namespace App\Http\Controllers\Consumer;

use App\Enums\SDPaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerSdPayment;
use App\Models\Consumer\ConsumerScheme;
use App\Models\Master\PaymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayDepositController extends Controller
{
    public function index()
    {
        return "PayDepositController";
    }

    /**
     * To Pay SD Deposit
     */
    public function edit($id) 
    {
        $payment_types = PaymentType::all();
        $consumer = Consumer::with(['scheme', 'sdPayment'])->find($id);
        return view('consumers.deposit-details.edit', [
            'consumer' => $consumer,
            'payment_types' => $payment_types,
        ]);
    }

    /**
     * To Update the SD Amount
     */
    public function update(Request $request, $id)
    {
        // Get consumer scheme details
        $consumer_scheme = ConsumerScheme::where('consumer_id', $id)->first();
        // Validation
        $request->validate([
            'amount' => ['required', 'numeric', 'gt:0', 'min:1', 'max:' . $consumer_scheme->balance],
            'payment_type' => 'required',
            'transaction_no' => 'required',
            'notes' => 'nullable',
        ]);

        // Amount Calculations
        $balance_amt = $consumer_scheme->balance - $request->amount;
        $paid_deposit = $consumer_scheme->paid_deposit + $request->amount;
        // Add Record to SD Payment
        ConsumerSdPayment::create([
            'consumer_id' => $id,
            'payment_type_id' => $request->payment_type,
            'transaction_number' => $request->transaction_no,
            'amount' => $request->amount,
            'balance' => $balance_amt,
            'status_id' => SDPaymentStatus::PAID->value, // 1:Paid
            'created_by' => Auth::id(),
        ]);
        // Update Consumer Scheme
        $consumer_scheme->update([
            'paid_deposit' => $paid_deposit,
            'balance' => $balance_amt,
            'status' => ($balance_amt == 0) ? 1 : 0, // 1 = Paid, 0 =Not Paid
        ]);

        // Response
        return response()->json(['success' => 'SD Payment updated successfully']);
    }
} 