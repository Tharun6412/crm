<?php
namespace App\Http\Controllers\Consumer;

use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\InvoicePayment;
use App\Models\Invoice\Ledger;
use App\Notifications\Consumer\AcceptSmsNotification;
use App\Notifications\Consumer\RejectSmsNotification;
use App\Services\LedgerService;
use App\Services\SmsService;
use Carbon\Carbon;
use Faker\Provider\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcceptController extends Controller
{
    /**
     * Index method
     */
    public function index(Request $request)
    {
        // return "Invoices Inserted";
    }
    /**
     * Consumer Scheme Accept State
     */
    public function edit(Request $request, $id) 
    {
        $consumer= Consumer::find($id);
        return view('consumers.accept.edit', [
            'consumer' => $consumer, 
            'id' => $id, 
        ]);
    }

    /**
     * Accept/Reject
     * Registered -> Accept/Reject
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|max:255',
            'status' => 'required',
        ]);
        // Consumer Status History 3= Accept, 9=Reject
        if($request->status == 1) {
            $con_status = EnumsConsumerStatus::ACCEPT->value;
            $status_val = "accepted";
        }else {
            $con_status = EnumsConsumerStatus::REJECT->value;
            $status_val = "rejected";
        }
        // Consumer Update
        $consumer = Consumer::find($id);
        $consumer->update([
            'status_id' => $con_status,
            'updated_by' => Auth::id(),
        ]);
        // Status History
        ConsumerStatus::create([
            'consumer_id' => $id,
            'status_id' => $con_status,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        // Sms Integration
        if($con_status == EnumsConsumerStatus::ACCEPT->value) {
            $sms_response = SmsService::dispatch($consumer, new AcceptSmsNotification(['crn' => $consumer->crn]));
        }else {
            $sms_response = SmsService::dispatch($consumer, new RejectSmsNotification(['crn' => $consumer->crn]));
        }
        // Response
        return response()->json(['success' => 'Consumer status updated Successfully!']);
    }
} 