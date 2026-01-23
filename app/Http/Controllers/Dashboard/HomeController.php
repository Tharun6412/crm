<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ComplaintStatus;
use App\Enums\InvoiceStatus;
use App\Enums\OtpPurpose;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Mail\User\RegisterOtpMail;
use App\Models\Consumer\Consumer;
use App\Models\Admin\User;
use App\Models\Complaint\Complaint;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\InvoicePayment;
use App\Notifications\RegisterOtpSMS;
use App\Services\EmailService;
use App\Services\OtpService;
use App\Services\SmsService;

class HomeController extends Controller
{
    /**
     * Index
     * 
     * Dashboard
     */
    public function index()
    {
        // Sample Email
        // $consumer = Consumer::find(1);
        // $user = User::find(1);
        // EmailService::dispatch($user, new RegisterOtpMail($user));
        
        // SmsService::send($user, new RegisterOtpSMS($user));
        
        // Test OTP
        // echo $otp = OtpService::create('9703722588', 'registration', 'user');
        // 519048, 295661, 272222, 230066
        // if(OtpService::verify('9703722588', 'registration', '230066', 'user'))
        //     echo 'YEs';
        // else
        //     echo 'No';
        // echo OtpPurpose::REGISTER->value;
        $consumer_count = Consumer::when((!isAdmin() AND !isSuperAdmin()), function ($q) {
            $q->whereIn('ga_id', session('user')['gas']);
        })->count();
        $invoices_count = BillInvoice::where('status_id', InvoiceStatus::NOT_PAID->value)->count();
        $payments_count = InvoicePayment::where('status_id', PaymentStatus::PROGRESS->value)->count();
        $calls_list = Complaint::when((!isAdmin() AND !isSuperAdmin()), function ($q) {
                $q->whereIn('ga_id', session('user')['gas']);
        })->count();
        return view('dashboard.home', [
            'consumer_count' => $consumer_count,
            'invoice_count' => $invoices_count,
            'payments_count' => $payments_count,
            'calls_list' => $calls_list,
        ]);
    }
}