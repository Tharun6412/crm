<?php

namespace App\Http\Controllers;

use App\Enums\ConsumerStatus;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\PaymentStatus;
use App\Enums\SegmentType;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\InvoicePayment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoicesSetController extends Controller
{
    public function show(Request $request, $ga)
    {
        $limit        = (int) $request->query('limit', 500);     // default batch: 500
        $offset       = (int) $request->query('offset', 0);
        if(!empty($ga)) {

            set_time_limit(0);

            $consumers = Consumer::where('ga_id', $ga) //Tumukur GA
                ->where('segment_id', SegmentType::DOMESTIC->value)
                ->whereIn('status_id', [
                    ConsumerStatus::ACTIVATE->value,
                    ConsumerStatus::TD->value,
                    ConsumerStatus::PD->value
                ])
                ->whereHas('invoices', function ($q) {
                    $q->where('prepaid', 1)
                    ->whereIn('type_id', [
                        InvoiceType::GAS_BILL->value,
                        InvoiceType::LATE_PAYMENT_CHARGES->value,
                        InvoiceType::RENTAL_CHARGES->value,
                        InvoiceType::SD_EMI->value,
                    ])
                    ->whereNot('status_id', InvoiceStatus::CANCEL->value)
                    ->where('invoice_date', '<=', '2026-04-07')
                    ->whereIn('status_id', [2, 3]); // unpaid or partial only
                })
                ->select('id')
                ->orderBy('id', 'asc')
                ->offset($offset)
                ->limit($limit)->get();

                if($consumers->isNotEmpty()) {
                    foreach ($consumers as $consumer) {

                        DB::beginTransaction();

                        try {

                            $invoices = BillInvoice::where('consumer_id', $consumer->id)
                                ->where('prepaid', 1)
                                ->whereIn('type_id', [
                                    InvoiceType::GAS_BILL->value,
                                    InvoiceType::LATE_PAYMENT_CHARGES->value,
                                    InvoiceType::RENTAL_CHARGES->value,
                                    InvoiceType::SD_EMI->value
                                ])
                                ->whereNot('status_id', InvoiceStatus::CANCEL->value)
                                ->where('invoice_date', '<=', '2026-04-07')
                                ->orderBy('invoice_date', 'asc')
                                ->orderBy('id', 'asc')
                                ->get([
                                    'id',
                                    'total_amount',
                                    'payable_amount',
                                    'paid_amount',
                                    'balance_amount',
                                    'status_id'
                                ]);

                            if ($invoices->isEmpty()) {
                                DB::commit();
                                continue;
                            }

                            $totalPayment = InvoicePayment::whereHas('invoice', function ($q) use ($consumer) {
                                    $q->where('consumer_id', $consumer->id)
                                    ->whereIn('type_id', [
                                        InvoiceType::GAS_BILL->value,
                                        InvoiceType::LATE_PAYMENT_CHARGES->value,
                                        InvoiceType::RENTAL_CHARGES->value,
                                        InvoiceType::SD_EMI->value
                                    ])
                                    ->whereNot('status_id', InvoiceStatus::CANCEL->value)
                                    ->where('invoice_date', '<=', '2026-04-07');
                                })
                                ->whereNot('status_id', PaymentStatus::REVERSAL->value)
                                ->sum('amount');

                            $remainingPayment = (float)$totalPayment;

                            foreach ($invoices as $invoice) {

                                $invoiceAmount = (float)$invoice->payable_amount;

                                if ($remainingPayment >= $invoiceAmount) {

                                    $paidAmount = $invoiceAmount;

                                    $balanceAmount = 0;

                                    $paymentStatus = 1; //Paid

                                    $remainingPayment -= $invoiceAmount;

                                }
                                elseif ($remainingPayment > 0) {

                                    $paidAmount = $remainingPayment;

                                    $balanceAmount
                                        = $invoiceAmount - $remainingPayment;

                                    $paymentStatus = 3; //Partial Paid

                                    $remainingPayment = 0;

                                }
                                else {

                                    $paidAmount = 0;

                                    $balanceAmount = $invoiceAmount;

                                    $paymentStatus = 2; //Not Paid
                                }
                                BillInvoice::where('id', $invoice->id)
                                    ->update([
                                        's_paid_amount'       => $paidAmount,
                                        's_balance_amount'    => $balanceAmount,
                                        's_payment_status'    => $paymentStatus,
                                        'iteration_balance' => $remainingPayment,
                                        'reconciliation_flag' => 1
                                    ]);
                            }

                            DB::commit();

                            Log::info('Consumer reconciled', [
                                'consumer_id' => $consumer->id
                            ]);

                        } catch (\Exception $e) {

                            DB::rollBack();

                            Log::error('Reconciliation failed', [
                                'consumer_id' => $consumer->id,
                                'message' => $e->getMessage()
                            ]);
                        }
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'Invoice reconciliation completed',
                        'limit'       => $limit,
                        'offset'      => $offset,
                        'processed'   => $consumers->count(),
                        'next_offset' => $consumers->count() === $limit ? $offset + $limit : null,
                        'has_more'    => $consumers->count() === $limit,
                    ]);
                }
                else {
                    return response()->json([
                        'status' => false,
                        'message' => 'No Consumers found',
                    ]);
                }
        }
        else {
             return response()->json([
                    'status' => false,
                    'message' => 'Geo Area id missing'
                ]);
        }
    }
}