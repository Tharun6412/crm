<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ComplaintStatus;
use App\Enums\ConnectionType;
use App\Enums\ConsumerStatus;
use App\Enums\InvoiceStatus;
use App\Enums\OtpPurpose;
use App\Enums\PaymentStatus;
use App\Enums\SegmentType;
use App\Http\Controllers\Controller;
use App\Mail\User\RegisterOtpMail;
use App\Models\Consumer\Consumer;
use App\Models\Admin\User;
use App\Models\Complaint\Complaint;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\InvoicePayment;
use App\Models\Master\ConnectionType as MasterConnectionType;
use App\Notifications\RegisterOtpSMS;
use App\Services\EmailService;
use App\Services\OtpService;
use App\Services\SmsService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Index
     * 
     * Dashboard
     */
    public function index(Request $request)
    {
        // Get counts
        $consumer_count = Consumer::selectRaw("
            COUNT(*) as total_count,
            SUM(CASE WHEN connection_type_id = ? AND status_id = ? THEN 1 ELSE 0 END) as postpaid_count,
            SUM(CASE WHEN connection_type_id = ? AND status_id = ? THEN 1 ELSE 0 END) as prepaid_count,
            SUM(CASE WHEN status_id = ? THEN 1 ELSE 0 END) as activation_count,
            SUM(CASE WHEN status_id = ? THEN 1 ELSE 0 END) as register_count,
            SUM(CASE WHEN status_id = ? THEN 1 ELSE 0 END) as td_count,
            SUM(CASE WHEN status_id = ? THEN 1 ELSE 0 END) as pd_count
        ", [
            ConnectionType::POSTPAID->value,
            ConsumerStatus::ACTIVATE->value,
            ConnectionType::PREPAID->value,
            ConsumerStatus::ACTIVATE->value,
            ConsumerStatus::ACTIVATE->value,
            ConsumerStatus::REGISTER->value,
            ConsumerStatus::TD->value,
            ConsumerStatus::PD->value
        ])
        ->first();
        // Render output
        return view('dashboard.home', [
            'consumer_count' => $consumer_count,
        ]);
    }
}