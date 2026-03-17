<?php
namespace App\Actions\Prepaid;

use App\Enums\MroStatus;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillMroData;
use App\Models\Invoice\BillMroDataHistory;

class MroPushAction 
{
    public static function process($consumerData)
    {
        $error_ar = ['mro_response' => []];

        foreach ($consumerData as $consumer) {
            if(!empty($consumer['mro_order_id'])){
                // consumer validation
                $consumerModel = Consumer::where('crn',$consumer['crn'])
                    ->whereHas('activeMeter',function($q) use($consumer){
                        $q->where('meter_serial_no',$consumer['meter_serial_no']);
                    })
                    ->first();
                if($consumerModel){
                    // check CRN + MRO match
                    $mro = BillMroData::where('mro_number',$consumer['mro_order_id'])
                        ->where('consumer_id',$consumerModel->id)
                        ->first();
                    if($mro){
                        // check processed payload
                        if($mro->status_id < 5){
                            // save staging data
                            $mro->update([
                                'mro_data' => json_encode($consumer),
                                'status_id' => MroStatus::RECEIVED->value,
                            ]);
                            BillMroDataHistory::insert([
                                'mro_data_id' => $mro->id,
                                'status_id' => MroStatus::RECEIVED->value,
                                'created_at' => now()
                            ]);
                            $error_ar['mro_response'][] = [
                                'crn' => $consumer['crn'],
                                'meter_serial_no' => $consumer['meter_serial_no'],
                                'mro_order_id' => $consumer['mro_order_id'],
                                'error_code' => 0,
                                'message' => 'Data received successfully'
                            ];
                        }else{
                            $error_ar['mro_response'][] = [
                                'crn' => $consumer['crn'],
                                'meter_serial_no' => $consumer['meter_serial_no'],
                                'mro_order_id' => $consumer['mro_order_id'],
                                'error_code' => 1,
                                'message' => 'This Mro order id is already exists'
                            ];
                        }
                    }else{
                        $error_ar['mro_response'][] = [
                            'crn' => $consumer['crn'],
                            'meter_serial_no' => $consumer['meter_serial_no'],
                            'mro_order_id' => $consumer['mro_order_id'],
                            'error_code' => 1,
                            'message' => 'CRN and Mro Order Id mis-match'
                        ];
                    }
                }else{
                    $error_ar['mro_response'][] = [
                        'crn' => $consumer['crn'],
                        'meter_serial_no' => $consumer['meter_serial_no'],
                        'mro_order_id' => $consumer['mro_order_id'],
                        'error_code' => 1,
                        'message' => 'Invalid Consumer details'
                    ];
                }
            }
            else{
                $error_ar['mro_response'][] = [
                    'crn' => $consumer['crn'] ?? null,
                    'meter_serial_no' => $consumer['meter_serial_no'] ?? null,
                    'mro_order_id' => '',
                    'error_code' => 1,
                    'message' => 'Mro Order Id is required'
                ];
            }
        }

        return $error_ar;
           
    }
}