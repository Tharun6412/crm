<?php

namespace App\Actions\Prepaid;

use App\Contracts\Prepaid\Mro;
use App\Enums\ConnectionType;
use App\Enums\ConsumerStatus;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillMroData;
use App\Models\Invoice\BillMroDataHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MroRequestAction
{
    /**
     * Fetch
     */
    public static function getConsumer()
    {
        $req_start_date = date('Y-m-d');
        $schedule_date = date('Y-m-t');
        // Get latest MRO requests of consumer
        $latestMro = BillMroData::selectRaw('MAX(created_at)')
            ->whereColumn('consumer_id', 'bil_mro_data.consumer_id');
        // Get consumers
        $consumers = Consumer::selectRaw('cns_consumers.id, cns_consumers.crn')
            ->join('cns_prepaid', 'cns_consumers.id', '=', 'cns_prepaid.consumer_id')
            ->leftJoin('bil_mro_data', function($join) use($latestMro) {
                $join->on('bil_mro_data.consumer_id', '=', 'cns_consumers.id')
                ->where('bil_mro_data.created_at', $latestMro)
                ->whereIn('bil_mro_data.status_id', [1, 2, 3]);
            })
            ->whereDate('cns_prepaid.hes_date', '<=', $req_start_date)
            ->whereNull('bil_mro_data.id')
            ->get();
        if($consumers) {
            // Create Batch ID with Str uuid and insert in bulk
            $batch_id = Str::uuid();
            // Prepare bulk insert array along with API input
            $mro_data_bulk = [];
            $mro_req_bulk = [];
            foreach($consumers as $consumer) {
                $mro_data_bulk[] = [
                    'consumer_id' => $consumer->id,
                    'schedule_date' => $schedule_date,
                    'status_id' => 1,
                    'batch_id' => $batch_id,
                ];
            }
            // Insert into MRO data
            $mro_data_batch_insert = BillMroData::insert($mro_data_bulk);
            if($mro_data_batch_insert)
            {
                // fetch inserted mro data for this batch.
                $insertedRows = BillMroData::where('batch_id', $batch_id)->get();
                $mro_req_bulk = [];
                $mro_update_bulk = [];
                $history_bulk = [];
                foreach ($insertedRows as $row) {
                    // MRO order id generation
                    $mro_order_id = 'MRO' . Str::padLeft($row->id, 9, '0');
                    // Update the MRO Order id to BillMroData table
                    $row->update(['mro_number' => $mro_order_id]);
                    // Data for the MRO Request API.
                    $mro_req_bulk[] = [
                        'mro_order_id' => $mro_order_id,
                        'mech_meter_serial_number' => $row->consumer->activeMeter->meter_no,
                        'prepaid_mod_number' => $row->consumer->activeMeter->meter_serial_no,
                        'scheduled_mr_date' => $row->schedule_date,
                        'crn' => $row->consumer->crn
                    ];
                    // Array for the MRO Data history.
                    $history_bulk[] = [
                        'mro_data_id' => $row->id,
                        'status_id' => 1,
                        'created_at' => now()
                    ];
                }
                // Insert into MRO data history
                $mro_history_batch_insert = BillMroDataHistory::insert($history_bulk);
                // Sending data to Call MRO Request API function.
                $reposnse = self::callMroRequest($mro_req_bulk, $batch_id);
            }
        }
        else{
            print "No consumers found for billing";
        }
    }

    public static function callMroRequest($api_data, $batch_id)
    {
        if($api_data) {
            $responses = Mro::request($api_data);
    
            foreach ($responses as $resp) {

                $mroData = BillMroData::where('mro_number', $resp['mro_order_id'])
                    ->where('batch_id', $batch_id)
                    ->first();

                if ($mroData) {
                    $status = $resp['status'] === 'success' ? 2 : 3;
                    $mroData->update([
                        'status_id' => $status,
                        'error_code'=> $resp['error_code'],
                        'error_message'=> $resp['error_message'] ?? null,
                    ]);    
                    BillMroDataHistory::create([
                        'mro_data_id' => $mroData->id,
                        'status_id'   => $status,
                    ]);
                }
                //  else case : MRO ordrer id not found.
            }
        }
        else {
            return response()->json(['msg' => 'No data to request'], 422);
        }
    }
}