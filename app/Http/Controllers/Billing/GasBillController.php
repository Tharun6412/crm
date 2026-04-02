<?php

namespace App\Http\Controllers\Billing;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Http\Controllers\Controller;
use App\Models\Invoice\BillInvoice;
use App\Models\Master\Price;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class GasBillController extends Controller
{
    /**
     * Show
     * 
     * Display gasbill
     * @param Int InvoiceId
     */
    public function show($id)
    {
        // Get Gas bill only
        $invoice = BillInvoice::where(['id' => $id, 'type_id' => InvoiceType::GAS_BILL->value])->first();
        $lpc = $invoice->childInvoices()->where('type_id',InvoiceType::LATE_PAYMENT_CHARGES->value)->first()?->balance_amount ?? 0;
        $emi = $invoice->childInvoices()->where('type_id',InvoiceType::SD_EMI->value)->first()?->balance_amount ?? 0;
        $rental = $invoice->childInvoices()->where('type_id',InvoiceType::RENTAL_CHARGES->value)->first();

        $billFrom = Carbon::parse($invoice->consumption->date_from);
        $billTo   = Carbon::parse($invoice->consumption->date_to);
        $runningDate = $billFrom->copy();
        $breakups = [];
        $details = $invoice->consumption->consumptionDetails;

        /** IMPORTANT: order by insertion or id */
        $details = $details->sortBy('id')->values();
        foreach ($details as $index => $row) {
            $startDate = $runningDate->copy();
            // normal end calculation
            $endDate = $startDate->copy()->addDays($row->days - 1);
            // safety clamp for last row
            if ($index === $details->count() - 1 || $endDate->gt($billTo)) {
                $endDate = $billTo->copy();
            }
            $breakups[] = [
                'start_date'  => $startDate->format('Y-m-d'),
                'end_date'    => $endDate->format('Y-m-d'),
                'days'        => $row->days,
                'consumption' => $row->consumption,
                'cf'          => $row->cf,
                'unit_price'  => $row->unit_price,
                'total_price' => $row->total_price,
            ];
            // move pointer for next slab
            $runningDate = $endDate->copy()->addDay();
        }
        // attach derived data (DO NOT persist)
        $invoice->consumption->breakupPeriods = $breakups;

        $last3Bills = BillInvoice::where('consumer_id', $invoice->consumer_id)
            ->where('type_id', InvoiceType::GAS_BILL->value)
            ->where('status_id', '!=', InvoiceStatus::CANCEL->value)
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        $avg_scm = $last3Bills->avg('net_consumption');
        $avg_scm_per_day = $last3Bills->avg(fn ($b) => $b->days > 0 ? $b->net_scm / $b->days : 0);

        $billHistory = $last3Bills->reject(fn ($bill) => $bill->id === $invoice->id)->take(2);
        $price = Price::where(['segment_id' => $invoice->consumer->segment_id, 'district_id' => $invoice->consumer->district_id])->first();


        // Check gas bill
        if(!$invoice)
            abort('403', 'Invalid invoice');

        // Set Locale for regional language
        App::setLocale($invoice->consumer->state->lang_code ?? 'tel');
        // Render output
        return view('billing.gas-bill.show', [
            'invoice' => $invoice, 
            'avg_scm' => $avg_scm, 
            'avg_scm_per_day' => $avg_scm_per_day,
            'price' => $price,
            'lpc' => $lpc,
            'emi' => $emi,
            'rental' => $rental,
            'billHistory' => $billHistory
            ]);
    }
}