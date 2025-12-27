<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Invoice\Ledger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LedgerController extends Controller
{
    public function index()
    {
        return "Ledger Report Details";
    }
    /**
     * Ledger Report
     * @param Int Consumer Id
     */
    public function show(Request $request, $id)
    {
        $ledger_report = Ledger::where('consumer_id', $id)->orderByDesc('id')->get();
        return view('consumers.consumers.show-ledger', [
            'ledger_report' => $ledger_report,
        ]);
    }
}