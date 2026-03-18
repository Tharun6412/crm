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
use App\Models\Master\Cluster;
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
        // Get Counts
        $consumer_count = Consumer::selectRaw("
            mst_gas.cluster_id, 
            cns_consumers.segment_id,
            COUNT(*) as total_count,
            SUM(CASE WHEN cns_consumers.connection_type_id = ? AND cns_consumers.status_id = ? THEN 1 ELSE 0 END) as postpaid_count,
            SUM(CASE WHEN cns_consumers.connection_type_id = ? AND cns_consumers.status_id = ? THEN 1 ELSE 0 END) as prepaid_count,
            SUM(CASE WHEN cns_consumers.status_id = ? THEN 1 ELSE 0 END) as execute_count,
            SUM(CASE WHEN cns_consumers.status_id = ? THEN 1 ELSE 0 END) as activation_count,
            SUM(CASE WHEN cns_consumers.status_id = ? THEN 1 ELSE 0 END) as register_count,
            SUM(CASE WHEN cns_consumers.status_id = ? THEN 1 ELSE 0 END) as td_count,
            SUM(CASE WHEN cns_consumers.status_id = ? THEN 1 ELSE 0 END) as pd_count
        ", [
            ConnectionType::POSTPAID->value,
            ConsumerStatus::ACTIVATE->value,
            ConnectionType::PREPAID->value,
            ConsumerStatus::ACTIVATE->value,
            ConsumerStatus::EXECUTE->value,
            ConsumerStatus::ACTIVATE->value,
            ConsumerStatus::REGISTER->value,
            ConsumerStatus::TD->value,
            ConsumerStatus::PD->value
        ])
        ->leftJoin('mst_gas', 'mst_gas.id', '=', 'cns_consumers.ga_id')
        ->groupBy('mst_gas.cluster_id','cns_consumers.segment_id')->get();

        // Data Preparation
        $data['total_postpaid'] = $data['total_prepaid'] = $data['total_count'] = 0;
        foreach($consumer_count as $count) {
            if (!isset($data['consumer_data'][$count->cluster_id])) {
                $data['consumer_data'][$count->cluster_id] = [
                    'registration_count' => 0,
                    'execution_count' => 0,
                    'activation_count' => 0,
                    'td_count' => 0,
                    'pd_count' => 0,
                ];
            }
            // Segment type totals
            if (!isset($data['consumer_segment'][$count->segment_id])) {
                $data['consumer_segment'][$count->segment_id] = [
                    'postpaid' => 0,
                    'prepaid' => 0,
                    'total' => 0
                ];
            }
            // Segment Wise Totals
            $data['consumer_segment'][$count->segment_id]['postpaid'] += $count->postpaid_count;
            $data['consumer_segment'][$count->segment_id]['prepaid'] += $count->prepaid_count;
            // Overall Totals
            $data['total_postpaid'] += $count->postpaid_count;
            $data['total_prepaid'] += $count->prepaid_count;
            $data['total_count'] += $count->total_count;
            // Cluster wise Totals
            $data['consumer_data'][$count->cluster_id]['registration_count'] += $count->register_count;
            $data['consumer_data'][$count->cluster_id]['execution_count'] += $count->execute_count;
            $data['consumer_data'][$count->cluster_id]['activation_count'] += $count->activation_count;
            $data['consumer_data'][$count->cluster_id]['td_count'] += $count->td_count;
            $data['consumer_data'][$count->cluster_id]['pd_count'] += $count->pd_count;
        }
        // dd($data);
        // Render output
        return view('dashboard.home', [
            'total_count' => $data['total_count'],
            'total_postpaid' => $data['total_postpaid'],
            'total_prepaid' => $data['total_prepaid'],
            'consumer_data' => $data['consumer_data'],
            'consumer_segment' => $data['consumer_segment'],
            'clusters' => Cluster::all(),
        ]);
    }
}