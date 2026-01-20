<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Payments\PaymentTransaction;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    /**
     * Index
     */
    public function index(Request $request)
    {
        // Get all transactions
        $transactions = PaymentTransaction::
            when($request->has('key'), function ($q) use($request) {
                $q->whereAny(['transaction_id', 'amount', 'transaction_ref', 'bank_ref', 'pg_ref_id','payment_mode'], 'like', '%' . $request->key . '%');
                $q->orWhereHas('consumer', function ($q) use($request) {
                    $q->where('crn', 'like', '%' . $request->key . '%');
                });
            })
            ->when($request->has('geo_area'), function($q) use($request) {
                $q->whereHas('consumer', function($q) use($request) {
                    $q->whereIn('ga_id', $request->geo_area);
                });
            })
            ->orderBy('created_at', 'desc')->paginate(50)->withQueryString();

        // Render output
        if($request->ajax())
            return view('payments.transactions.list-body', ['transactions' => $transactions]);
        else
            return view('payments.transactions.list', ['transactions' => $transactions]);
    }

    /**
     * Show
     */
    public function show($id)
    {
        // Get transaction details
        $transaction = PaymentTransaction::find($id);

        // Render output
        return view('payments.transactions.show', ['transaction' => $transaction]);
    }
}