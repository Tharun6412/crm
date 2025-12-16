<?php
namespace App\Http\Controllers\Consumer;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DocumentCentre\DocumentUpload;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerDocument;
use App\Models\Consumer\ConsumerRefund;
use App\Models\Consumer\ConsumerRefundStatus;
use App\Models\Consumer\ConsumersScheme;
use App\Models\Consumer\ConsumersStatus;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\InvoicePayment;
use App\Models\Master\BillInvoiceType;
use App\Models\Master\PaymentType;
use App\Services\InvoiceGeneration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    /**
     * Refund Consumers List
     * 1 = Refund Request
     * 2 = Process
     * 3 = Approve
     * 4 = Closed
     */
    public function index(Request $request)
    {
        $refunds_list = ConsumerRefund::with(['consumer'])->when($request->has('key'), function ($q) use($request) {
                $q->whereAny(['request_no'], 'like', '%' . $request->key . '%');
            })->paginate(50)->withQueryString();
        
        // Render output
        if($request->ajax()) {
            return view('consumers.refund.list-body', [
                'refunds_list' => $refunds_list,
            ]);
        }else {
            return view('consumers.refund.list', [
                'refunds_list' => $refunds_list
            ]);
        }
    }

    /**
     * To show the refund details
     */
    public function show(Request $request, $id)
    {
        $refund_data = ConsumerRefund::with([
            'consumer:id,fname,lname,segment_id,crn,status_id',
            'consumer.segment:id,name',
            'consumer.status:id,name',
            'consumer.scheme:id,consumer_id,scheme_id,security_deposit,consumption_deposit,total_deposit,paid_deposit,balance',
            'consumer.scheme.scheme:id,name',
            'status:id,name',
            'refundStatus:id,refund_id,status_id,notes,created_at,created_by',
            'refundStatus.createdBy:id,first_name,last_name',
            'refundStatus.status:id,name',
        ])->find($id);
        return view('consumers.refund.show', ['refund_data' => $refund_data]);
    }

    /**
     * Inititate Refund Request 
     */
    public function refundRequest(Request $request, $id) 
    {
        $consumer_scheme = ConsumersScheme::where('consumer_id', $id)->first();
        $refund_data = ConsumerRefund::where('consumer_id', $id)->first();

        return view('consumers.refund.create', [
            'id' => $id, 
            'consumer_scheme' => $consumer_scheme,
            'refund_data' => $refund_data,
        ]);
    }

    /**
     * Refund Request Updated
     */
    public function refundRequestUpdate(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required',
        ]);
        // Refund Data
        $add_refund = ConsumerRefund::create([
            'consumer_id' => $id,
            'status_id' => 1, //Refund Request
            'created_by' => Auth::id(),
        ]);
        // Request Number Generation
        $request_number_create = "R".str_pad($add_refund->id, 6,0,STR_PAD_LEFT);
        ConsumerRefund::where('id', $add_refund->id)->update(['request_no' => $request_number_create]);
        // Refund Status
        ConsumerRefundStatus::create([
            'refund_id' => $add_refund->id,
            'status_id' => '1', //1 = Refund Request 
            'notes' => !empty($request->notes) ? $request->notes : null,
            'created_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'Consumer refund requested successfully']);
    }

    /**
     * Refund Process
     */
    public function process($id)
    {
        $refund_data = ConsumerRefund::find($id);
        $inv_types = BillInvoiceType::all();
        $invoice_data = BillInvoice::select('type_id', DB::raw('SUM(IF(balance_amount IS NULL, 0, balance_amount)) as balance_amount'))->where('status_id', '!=', 1)->where('consumer_id', $refund_data->consumer_id)->groupBy('type_id')->get()->pluck('balance_amount', 'type_id');
        // dd($invoice_data);
        return view('consumers.refund.process', [
            'refund_data' => $refund_data,
            'inv_types' => $inv_types,
            'invoice_data' => $invoice_data,
        ]);
    }

    /**
     * Update Refund Process
     */
    public function processUpdate(Request $request, $id)
    {
        // Validation
        $request->validate([
            'disconnect_amt' => 'required|numeric|min:0',
        ]);

        $refund_data = ConsumerRefund::find($id);
        $balance = BillInvoice::select(DB::raw('GROUP_CONCAT(id) as ids'), DB::raw('SUM(balance_amount) as balance_amount'))->where('status_id', '!=', 1)->where('consumer_id', $refund_data->consumer_id)->first();
        //Refund Calculations
        $balance_charges = $balance->balance_amount + $request->disconnect_amt;
        $tot_refund_amt = $refund_data->consumer->scheme->paid_deposit - $balance_charges;
        // Add SI if disconnection charges applicable
        if($request->disconnect_amt > 0) {
            // Service Invoice Generation
            $amt = $request->disconnect_amt;
            $gst_calculated_amt = 1.18; //(1+18%)
            $base_amt = round($amt/$gst_calculated_amt, 3);
            $tax_amt = round($amt - $base_amt, 3);
            $invoice_details = array(
                'type_id' => 2, //Service Invoice
                'consumer_id' => $refund_data->consumer_id,
                'invoice_date' => Carbon::now()->toDateString(),
                'base_amount' => $base_amt,
                'taxable_amount' => $base_amt,
                'tax_id' => 2,
                'tax_value' => 18,
                'tax_amount' => $tax_amt,
                'total_amount' => $amt,
                'paid_amount' => $amt,
                'balance_amt' => 0,
                'status_id' => 1, //Paid
                'created_by' => Auth::id(),
            );
            $inv_number_details = array(
                'state_id' => $refund_data->consumer->ga->state_id,
                'inv_type' => 1,
                'state_code' => $refund_data->consumer->ga->state->code,
            );
            $inv_id = InvoiceGeneration::serviceInvoiceGenerate($invoice_details, $inv_number_details);
            // Adding to Invoice Payment
            InvoicePayment::create([
                'invoice_id' => $inv_id,
                'payment_date' => Carbon::now()->toDateString(),
                'payment_type_id' => 13,
                'transaction_id' => "SD Refund",
                'amount' => $amt,
                'balance' => 0,
                'status_id' => 1,//completed
                'notes' => !empty($request->notes) ? $request->notes : null,
                'created_by' => Auth::id(),
            ]);
            // InvoicePayment Update
            $ids = $balance->ids ? explode(',', $balance->ids) : [];
            if(!empty($ids)) {
                InvoicePayment::whereIn('invoice_id', $ids)->update(['payment_type_id' => 13, 'status_id' => 1, 'balance' => 0]);
            }
        }
        // Refund Data 
        $add_refund = ConsumerRefund::where('id', $id)->update([
            'sd_paid' => $refund_data->consumer->scheme->paid_deposit,
            'outstanding_amount' => $balance->balance_amount,
            'disconnection_amount' => $request->disconnect_amt,
            'invoice_id' => isset($inv_id) ? $inv_id : null,
            'refund_amount' => $tot_refund_amt,
            'status_id' => 2,
            'created_by' => Auth::id(),
        ]);
        // Refund Status
        ConsumerRefundStatus::create([
            'refund_id' => $id,
            'status_id' => '2', //2 = Process
            'created_by' => Auth::id(),
        ]);
        // Response
        return response()->json(['success' => 'Consumer refund processed Successfully.']);
    }

    /**
     * Approve Stage
     */
    public function approve($id)
    {
        $refund_data = ConsumerRefund::find($id);
        return view('consumers.refund.approve', [
            'refund_data' => $refund_data,
        ]);
    }

    /**
     * Update the refund status to Approve
     */
    public function approveUpdate(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required',
        ]);
        ConsumerRefund::where('id', $id)->update([
            'status_id' => '3'
        ]);
        // Refund Status
        ConsumerRefundStatus::create([
            'refund_id' => $id,
            'status_id' => '3', //3 = Approve
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'Consumer refund approved successfully']);
    }

    /**
     * Close Refund Request
     */
    public function close($id)
    {
        $refund_data = ConsumerRefund::find($id);
        return view('consumers.refund.close', [
            'refund_data' => $refund_data,
            'payment_types' => PaymentType::all(),
        ]);
    }

    /**
     * Update the refund status to close
     */
    public function closeRefund(Request $request, $id)
    {
        $request->validate([
            'payment_type' => 'required',
            'transaction_date' => 'required|before_or_equal:today',
            'transaction_no' => 'required',
            'notes' => 'required',
        ]);
        // ConsumerRefund
        ConsumerRefund::where('id', $id)->update([
            'payment_type_id' => $request->payment_type,
            'transaction_id' => $request->transaction_no,
            'transaction_date' => Carbon::createFromFormat('d-m-Y', $request->transaction_date),
            'status_id' => '4'
        ]);
        // Refund Status
        ConsumerRefundStatus::create([
            'refund_id' => $id,
            'status_id' => '4', //4 = Closed
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'Consumer refund closed successfully']);
    }
} 