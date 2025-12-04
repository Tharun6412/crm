<?php
namespace App\Http\Controllers\Consumer;
use App\Http\Controllers\Controller;
use App\Models\Consumer\CaCounter;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumersScheme;
use App\Models\Consumer\ConsumersStatus;
use App\Models\Invoice\BillInvoice;
use App\Models\Master\PaymentType;
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
        echo "test method";
    }
    /**
     * TO Get the Deposit Details
     * Consumer Scheme Details
     */
    public function edit(Request $request, $id) 
    {
        $payment_types = PaymentType::all();
        $consumer_scheme = ConsumersScheme::with(['scheme'])->where('consumer_id', $id)->first();
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
        $consumer_scheme = ConsumersScheme::where('consumer_id', $id)->first();
        $request->validate([
            'amount' => ['required', 'numeric', 'gt:0', 'min:' . $consumer_scheme->scheme->min_payment],
            'payment_type' => 'required',
            'transaction_no' => 'required',
            'notes' => 'nullable',
        ]);
        $district_code = $consumer_scheme->consumer->district->code;
        $segment_type = $consumer_scheme->consumer->segment_id;
        $ca_code = $consumer_scheme->consumer->ca->code;
        if(!empty($district_code) and !empty($ca_code)) {
            // Consumer Number Generation
            $ca_data = CaCounter::select('count')->where('ca_id', $consumer_scheme->consumer->ca_id)->first();
            if(!empty($ca_data->count)) {
                $ca_count = $ca_data->count+1;
            }else {
                $ca_count = 1;
            }
            CaCounter::create([
                'ca_id' => $consumer_scheme->consumer->ca_id,
                'count' => $ca_count,
            ]);
            $crn_no = $district_code.$segment_type.str_pad($ca_code, 2, 0,STR_PAD_LEFT).str_pad($ca_count, 6, "0", STR_PAD_LEFT);
            Consumer::where('id', $id)->update([
                'crn' => $crn_no,
                'status_id' => 2,
                'updated_by' => Auth::id(),
            ]);
            // Scheme Details
            // Paid Amount = Amount - Minimun Payment
            $paid_amt = $request->amount - $consumer_scheme->scheme->min_payment;
            $balance_amt = $consumer_scheme->balance - $paid_amt;
            if($paid_amt > 0) {
                // Scheme Payment Updated
                $consumer_scheme->update([
                    'paid_deposit' => $paid_amt,
                    'balance' => $balance_amt,
                    'status' => ($balance_amt == 0) ? 2 : 1, // 1 = Partially Paid, 2 = Paid
                ]);
            }
            // Service Invoice Generation
            // Calculations
            $amt = $consumer_scheme->scheme->min_payment;
            $gst_calculated_amt = 1.18; //(1+18%)
            $base_amt = round($amt/$gst_calculated_amt, 3);
            $tax_amt = round($amt - $base_amt, 3);
            $add_service_invoice = BillInvoice::create([
                'type_id' => 1, //Service Invoice
                'consumer_id' => $consumer_scheme->consumer_id,
                'invoice_date' => Carbon::now()->toDateString(),
                'base_amount' => $base_amt,
                'taxable_amount' => $base_amt,
                'tax_id' => 1,
                'tax_value' => 18,
                'tax_amount' => $tax_amt,
                'total_amount' => $amt,
                'paid_amount' => $amt,
                'balance_amt' => 0,
                'status_id' => 1, //Paid
                'created_by' => Auth::id(),
            ]);
            $inv_number = "SI".str_pad($add_service_invoice->id, 5, "0", STR_PAD_LEFT);
            BillInvoice::where('id', $add_service_invoice->id)->update(['invoice_number' => $inv_number]);
            // Consumer Status History
            ConsumersStatus::create([
                'consumer_id' => $consumer_scheme->consumer_id,
                'status_id' => 2,
                'notes' => !empty($request->notes) ? $request->notes : null,
                'created_by' => Auth::id(),
            ]);
            // Response
            return response()->json(['success' => 'CRN Created Successfully with ' . $crn_no . ', click <a href="'.url('consumers').'">here</a> to go to consumers list.']);
        }else {
            return response()->json(['success' => 'CRN number cannot be generated. Please contact administrator.']); 
        }
    }
} 