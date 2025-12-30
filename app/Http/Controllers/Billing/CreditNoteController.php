<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\CreditItem;
use App\Models\Invoice\CreditNote;
use App\Models\Invoice\Ledger;
use App\Models\Master\Tax;
use App\Services\LedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditNoteController extends Controller
{
    /**
     * Index
     * @param Int Invoice Id
     */
    public function index($id)
    {
        // Get the invoice details
        $invoice = BillInvoice::find($id);
        $taxes = Tax::all();

        if(!$invoice)
            abort(403, 'Invalid invoice');
        
        // Get past credit notes
        $credit_notes = CreditNote::where('invoice_id', $id)->orderBy('created_at', 'desc')->get();

        // Render output
        return view('billing.credit-note.list', [
            'invoice' => $invoice,
            'credit_notes' => $credit_notes,
            'taxes' => $taxes,
        ]);
    }

    /**
     * Store credit note
     */
    public function store(Request $request, $id)
    {
        // Validation
        $request->validate([
            'note_type' => 'required',
            'description.*' => 'required',
            'price.*' => 'required',
            'qty.*' => 'required',
            'tax_value' => 'required',
        ]);
        // Prepare data
        $cr_items = [];
        $cr_total = 0;
        foreach($request->price as $in => $price) {
            $item_price = $request->price[$in] * $request->qty[$in];
            $cr_total += $item_price;
            $cr_items[] = [
                'description' => $request->description[$in],
                'unit_price' => $request->price[$in],
                'quantity' => $request->qty[$in],
                'total_price' => $item_price,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $tax_amount = $cr_total * ($request->tax_value / 100);
        $total = $cr_total + $tax_amount;

        //-- Store data
        // Creadit notes
        $new_note = CreditNote::create([
            'code' => '123',
            'invoice_id' => $id,
            'type' => $request->note_type,
            'base_amount' => $cr_total,
            'tax_id' => $request->tax_id,
            'tax_value' => $request->tax_value,
            'tax_amount' => $tax_amount,
            'total_amount' => $total,
            'status' => 1,
            'created_by' => Auth::id(),
        ]);
        // Update credit note code
        $new_note->code = time();
        $new_note->save();

        // Push note_id into items table data
        foreach($cr_items as &$item)
        {
            $item['credit_note_id'] = $new_note->id;
        }

        // Credit note item
        $new_note_items = CreditItem::insert($cr_items);

        // Adjust invoice balances
        $eff_total = ($request->note_type == 1) ? -($total) : $total;
        $invoice = BillInvoice::find($id);
        $invoice->credit_amount = (($invoice->credit_amount) ? $invoice->credit_amount : 0) + ($eff_total);
        $invoice->total_amount = $invoice->total_amount + ($eff_total);
        $invoice->balance_amount = $invoice->balance_amount + ($eff_total);
        $invoice->save();
        // Add Ledger Report
        $ledger = Ledger::where('consumer_id', $new_note->invoice->consumer_id)->latest('created_at')->first();
        $creditNote = CreditNote::find($new_note->id);
        $balance_pay = $ledger?->balance ? $ledger->balance - $creditNote->total_amount : $creditNote->total_amount;
        $ledger_data[] = [
            'model' => $creditNote,
            'consumer_id' => $creditNote->invoice->consumer_id,
            'description' => "Credit/Debit Note: Invoice Generated with Invoice No.",
            'credit' => $creditNote->total_amount,
            'debit' => NULL,
            'balance' => $balance_pay,
        ];
        // Call Ledger Service
        LedgerService::create($ledger_data);
        // Response
        return response()->json(['success' => 'Note created successfully!']);
    }

    /**
     * Show
     * Credit note details
     * 
     * @param int credit_note_id
     */
    public function show($id)
    {
        $note = CreditNote::find($id);

        if(!$note)
            abort(430, 'Credit Note not found!');

        // Render output
        return view('billing.credit-note.show', ['note' => $note]);
    }

    /**
     * Show Credit NOte Details
     * #consumer Tab
     * @param int consumer_id
     */
    public function showCreditByConsumerId(Request $request, $id)
    {
        // Get the invoice details
        $taxes = Tax::all();
        // Get past credit notes
        $credit_notes = CreditNote::whereHas('invoice', function($q) use($id) {
            $q->where('consumer_id', $id);
        })->orderBy('created_at', 'desc')->get();

        // Render output
        return view('consumers.consumers.show-credit', [
            'credit_notes' => $credit_notes,
            'taxes' => $taxes,
        ]);
    }
}