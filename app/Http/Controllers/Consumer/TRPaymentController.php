<?php

namespace App\Http\Controllers\Consumer;

use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\InvoiceItem;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\PaymentStatus;
use App\Enums\SDPaymentStatus;
use App\Enums\TaxType;
use App\Http\Controllers\Controller;
use App\Models\Consumer\CaCounter;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerSdPayment;
use App\Models\Consumer\ConsumerScheme;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Invoice\InvoicePayment;
use App\Models\Invoice\Ledger;
use App\Models\Master\PaymentType;
use App\Notifications\Consumer\AcceptSmsNotification;
use App\Notifications\Consumer\WelcomeSmsNotification;
use App\Services\ConsumerStatusService;
use App\Services\InvoiceService;
use App\Services\LedgerService;
use App\Services\PaymentService;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TRPaymentController extends Controller
{
    /**
     * Index method
     */
    public function index(Request $request)
    {
        return "TR Controller";
    }

    /**
     * TO Get the Deposit Details
     * Consumer Scheme Details
     */
    public function edit(Request $request, $id) 
    {
        $payment_types = PaymentType::all();
        $consumer_scheme = ConsumerScheme::with(['scheme'])->where('consumer_id', $id)->first();
        // print "<pre>"; print_r($consumer_scheme);
        return view('consumers.deposit-details.pay', [
            'consumer_scheme' => $consumer_scheme,
            'payment_types' => $payment_types,
        ]);
    }

    /**
     * Service Invoice Generation
     * @string CRN Number generation
     * TR -> Registered
     */
    public function update(Request $request, $id)
    {
        // Get consumer scheme and scheme details
        $consumer_scheme = ConsumerScheme::where('consumer_id', $id)->first();
        if($consumer_scheme->scheme_id != NULL) {
            $min_payment = $consumer_scheme->scheme->min_payment ?? 0;
            $max_payment = ($consumer_scheme->scheme->registration + $consumer_scheme->security_deposit + $consumer_scheme->consumption_deposit) ?? 0;
            // $max_payment = ($consumer_scheme->scheme->registration + $consumer_scheme->scheme->security + $consumer_scheme->scheme->consumption) ?? 0;
        }else {
            $min_payment = 0;
            $max_payment = $consumer_scheme->total_deposit ?? 0;
        }
        
        // Validations
        $request->validate([
            'amount' => ['required', 'numeric', 'gt:0', 'min:' . $min_payment, 'max:' . $max_payment],
            'payment_type' => 'required',
            'transaction_no' => 'required',
            'notes' => 'nullable',
        ]);

        // Check for CRN generation
        $district_code = $consumer_scheme->consumer->district->code;
        $ca_code = $consumer_scheme->consumer->ca->code;
        $segment_type = $consumer_scheme->consumer->segment_id;
        if(!empty($district_code) and !empty($ca_code)) {
            //-- CRN Generation
            $ca_data = CaCounter::firstOrNew(['ca_id' => $consumer_scheme->consumer->ca_id]);
            $ca_data->count = ($ca_data->count ?? 0) + 1;
            $ca_data->save();
            // Generate CRN and update
            $crn_no = $district_code.$segment_type.str_pad($ca_code, 2, 0,STR_PAD_LEFT).str_pad($ca_data->count, 6, "0", STR_PAD_LEFT);
            Consumer::where('id', $id)->update([
                'crn' => $crn_no,
                'status_id' => EnumsConsumerStatus::REGISTER->value,
                'updated_by' => Auth::id(),
            ]);
            // Consumer Target Status 
            $target_status = ConsumerStatusService::update($id, EnumsConsumerStatus::REGISTER->value);
            // Scheme Details
            // Paid Amount = Amount - Minimun Payment
            $paid_amt = $request->amount - $consumer_scheme->scheme->registration;
            $balance_amt = $consumer_scheme->balance - $paid_amt;
            if($paid_amt > 0) {
                // Scheme Payment Updated
                $consumer_scheme->update([
                    'paid_deposit' => $paid_amt,
                    'balance' => $balance_amt,
                    'status' => ($balance_amt == 0) ? 1 : 0, // 1:Paid, 0:Not Paid
                ]);
                // Add Record to SD Payment
                ConsumerSdPayment::create([
                    'consumer_id' => $consumer_scheme->consumer_id,
                    'payment_type_id' => $request->payment_type,
                    'transaction_number' => $request->transaction_no,
                    'amount' => $paid_amt,
                    'balance' => $balance_amt,
                    'status_id' => SDPaymentStatus::PAID->value,
                    'created_by' => Auth::id(),
                ]);
            }

            //-- Generate service invoice for registration
            // Calculations
            $amt = $consumer_scheme->scheme->registration ?? 0;
            $gst_calculated_amt = 1.18; //(1+18%)
            $base_amt = round($amt / $gst_calculated_amt, 3);
            $tax_amt = round($amt - $base_amt, 3);
            if($amt > 10) {
                $invoice_items[] = [
                    'item_id' => InvoiceItem::DOMESTIC_REGISTRATION->value,
                    'quantity' => 1,
                    'unit_price' => $base_amt,
                    'total_price' => $base_amt,
                    'created_at' => Carbon::now(),
                ];
                $invoice_data = [
                    'config' => [
                        'state_id' => $consumer_scheme->consumer->ga->state_id,
                        'tax_id' => TaxType::GST->value, //GST = 2
                    ],
                    'headers' => [
                        'type_id' => InvoiceType::SERVICE_INVOICE->value, // 2 = Service Invoice
                        'consumer_id' => $consumer_scheme->consumer_id,
                        'invoice_date' => Carbon::now()->toDateString(),
                        'base_amount' => $base_amt,
                        'taxable_amount' => $base_amt,
                        'tax_id' => TaxType::GST->value,
                        'tax_value' => 18,
                        'tax_amount' => $tax_amt,
                        'total_amount' => $amt,
                        'payable_amount' => $amt,
                        'paid_amount' => 0,
                        'balance_amount' => $amt,
                        'status_id' => InvoiceStatus::NOT_PAID->value, // Paid
                        'created_by' => Auth::id(),
                    ],
                    'items' => $invoice_items,
                ];
                // Generate Invoice with Invoice Service
                $inv_number = InvoiceService::create($invoice_data);
                // Adding payment record for service invoice
                $inv_payment = PaymentService::create([
                    'invoice_id' => $inv_number['invoice_id'],
                    'payment_date' => Carbon::now()->toDateString(),
                    'payment_type_id' => $request->payment_type,
                    'transaction_id' => $request->transaction_no,
                    'amount' => $amt,
                    'status_id' => PaymentStatus::COMPLETED->value,
                    'notes' => !empty($request->notes) ? $request->notes : null,
                    'created_by' => Auth::id(),
                ]);
            }
            // Add consumer status history record
            ConsumerStatus::create([
                'consumer_id' => $consumer_scheme->consumer_id,
                'status_id' => EnumsConsumerStatus::REGISTER->value,
                'notes' => !empty($request->notes) ? $request->notes : null,
                'created_by' => Auth::id(),
            ]);

            // SMS and Email to send
            $sms_response = SmsService::dispatch($consumer_scheme->consumer, new WelcomeSmsNotification(['crn' => $crn_no]));

            // Response
            return response()->json(['success' => 'CRN generated successfully with ' . $crn_no . ', click <a href="'.url('consumers').'">here</a> to go to consumers list.']);
        }
        else {
            return response()->json(['success' => 'CRN cannot be generated, please contact administrator.']); 
        }
    }
} 