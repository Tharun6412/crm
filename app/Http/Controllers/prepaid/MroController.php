<?php
namespace App\Http\Controllers\prepaid;

use App\Contracts\Prepaid\Mro;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillMroData;
use App\Models\Invoice\BillMroDataHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MroController extends Controller
{
    /**
     * Mro Request API
     */
    public function mroRequest(Request $request)
    {
        // $consumer = Consumer::whereHas('invoices', function($q) {
        //     $q->where('type_id', InvoiceType::GAS_BILL->value);
        //     $q->whereNot('status_id', InvoiceStatus::CANCEL->value);
        // })->where('connection_type_id',2)->get();
        $today_date = Carbon::now()->addDays(2);
        $thresholdDate = $today_date->copy()->subDays(30);
        $consumers = Consumer::where(function ($query) use ($thresholdDate) {
            // 1. Invoice exists → validate invoice_date
            $query->whereHas('invoices', function ($q) use ($thresholdDate) {
                $q->whereDate('invoice_date', '<=', $thresholdDate);
            });
        })
        ->orWhere(function ($query) use ($thresholdDate) {
            // 2️. No invoice exists → fallback to prepaid
            $query->whereDoesntHave('invoices')
                ->whereHas('prepaid', function ($q) use ($thresholdDate) {
                    $q->whereDate('hes_date', '<=', $thresholdDate);
                });
        })
        ->get();

        if($consumers) {
            foreach ($consumers as $key => $con) {
                $order_id = 'MRO'. str_replace("-","", Str::orderedUuid());// create unique mro order id.
                $con_ar[] = [
                    'mro_order_id' => $order_id,
                    'mech_meter_serial_number' => $con['meter_no'],
                    'prepaid_mod_number' => $con['meter_sr_no'],
                    'scheduled_mr_date' => $today_date->toDateString(),
                    'crn' => $con['code']
                ];
                // insertion array for database
                $mro_req = [
                    'consumer_id' => $con['id'],
                    'mro_number' => $order_id,
                    'scheduled_date' => $today_date->toDateString(),
                    'created_at' => date('Y-m-d H:i:s'),
                    'status_id' => 1,  // Request Created.
                ];
                if(isset($mro_req) and !empty($mro_req)) {
                    $mro_data = BillMroData::create($mro_req);
                    if ($mro_data) {
                        // array for status history
                        $status_his = [
                            'mro_data_id' => $mro_data->id,
                            'status_id' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                        ];
                        BillMroDataHistory::create($status_his);
                    }
                }
            }
        }

        if (isset($con_ar) and !empty($con_ar)) {
            $mro_request = new Mro;
            $mro_resp = $mro_request()->request($con_ar);
            $response_decode = $mro_resp;

            // update the mro request table based on the response.
            $req_update =  array();
            $req_update_his =  array();
            if(isset($response_decode['MT_MRO_Response']) and !empty($response_decode['MT_MRO_Response'])) {
                if(isset($response_decode['MT_MRO_Response']['MRO_Response']) and !empty($response_decode['MT_MRO_Response']['MRO_Response'])) {
                    foreach ($response_decode['MT_MRO_Response']['MRO_Response'] as $key => $mro_response) {
                        switch ($mro_response['error_code']) {
                            case '0':
                                $ack_status = 2;
                                $req_resp = $mro_response['message'];
                                break;
                            case '1':
                                $ack_status = 3;
                                $req_resp = $mro_response['error'];
                                break;
                            default:
                                $ack_status = 1;
                                $req_resp = '';
                                break;
                        }

                        // Update of staging table with mro_order_id
                        $req_update[] = [
                            'mro_number' => $mro_response['mro_order_id'],
                            'status_id'=> $ack_status, // 3 = request response failure, 2 = request response success, 1 = request sent.
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
                        // Insertion array for status history insertion.
                        $req_update_his[] = [
                            'mro_order_id' => $mro_response['mro_order_id'],
                            'status_id'=> $ack_status,
                            'notes' => $req_resp,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                    }
                    // Update batch.
                    // Insert batch (history.)
                }
            }
        }
    }
}