<?php

namespace App\Actions\Prepaid;

use App\Contracts\Prepaid\Mro;
use App\Enums\ConnectionType;
use App\Enums\ConsumerStatus;
use App\Enums\MroStatus;
use App\Helpers\ApiLogger;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillMroBatch;
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
        $prevMonth = now()->subMonth();
        $req_start_date = $prevMonth->copy()->setDay(15)->toDateString();
        $schedule_date  = $prevMonth->copy()->endOfMonth()->toDateString();

        // Get latest MRO requests of consumer
        $latestMro = BillMroData::selectRaw('MAX(created_at)')
            ->whereColumn('consumer_id', 'bil_mro_data.consumer_id');
        // Get consumers
        $consumers = Consumer::selectRaw('cns_consumers.id, cns_consumers.crn')
            ->join('cns_prepaid', 'cns_consumers.id', '=', 'cns_prepaid.consumer_id')
            ->leftJoin('bil_mro_data', function($join) use($latestMro) {
                $join->on('bil_mro_data.consumer_id', '=', 'cns_consumers.id')
                ->where('bil_mro_data.created_at', $latestMro)
                ->whereIn('bil_mro_data.status_id', [MroStatus::REQUESTED->value, MroStatus::REQUEST_ACK_FAIL->value, MroStatus::RECEIVED->value, MroStatus::PROCESS_FAIL->value]); // 1 = requested, 2 = request_fail, 3 = data received, 4 = bill_process_fail
            })
            ->whereDate('cns_prepaid.hes_date', '<=', $req_start_date)
            ->whereNull('bil_mro_data.id')
            ->where('cns_consumers.status_id',ConsumerStatus::ACTIVATE->value)
            ->get();
        if($consumers->isNotEmpty()) {
            try {
                // Create Batch ID with Str uuid and insert in bulk
                $batch_id = Str::uuid();
                $batch_ar = BillMroBatch::create([
                    'batch_id' => $batch_id,
                    'schedule_date' => $schedule_date,
                ]);
                // Prepare bulk insert array along with API input
                $mro_data_bulk = [];
                $mro_req_bulk = [];
                foreach($consumers as $consumer) {
                    $mro_data_bulk[] = [
                        'consumer_id' => $consumer->id,
                        'schedule_date' => $schedule_date,
                        'status_id' => MroStatus::REQUESTED->value,
                        'mro_batch_id' => $batch_ar->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                // Insert into MRO data
                $mro_data_batch_insert = BillMroData::insert($mro_data_bulk);
                if($mro_data_batch_insert)
                {
                    // Instead of updating the mro order id row by row. Update the mro_order_id using this DB raw.
                    DB::update("UPDATE bil_mro_data SET mro_number = CONCAT('MRO', LPAD(id, 9, '0')) WHERE mro_batch_id = ?", [$batch_ar->id]);
                    // fetch inserted mro data for this batch.
                    $insertedRows = BillMroData::with('consumer.activeMeter')->where('mro_batch_id', $batch_ar->id)->get();
                    $mro_req_bulk = [];
                    $history_bulk = [];
                    foreach ($insertedRows as $row) {
                        // MRO order id generation
                        // $mro_order_id = 'MRO' . Str::padLeft($row->id, 9, '0');
                        // Update the MRO Order id to BillMroData table
                        // $row->update(['mro_number' => $mro_order_id]);
                        $meter = $row->consumer?->activeMeter;
                        // Data for the MRO Request API.
                        $mro_req_bulk[] = [
                            'mro_order_id' => $row->mro_number,
                            'mech_meter_serial_number' => $meter?->meter_no,
                            'prepaid_mod_number' => $meter?->meter_serial_no,
                            'scheduled_mr_date' => $row->schedule_date?->format('Y-m-d'),
                            'crn' => $row->consumer->crn
                        ];
                        // Array for the MRO Data history.
                        $history_bulk[] = [
                            'mro_data_id' => $row->id,
                            'status_id' => MroStatus::REQUESTED->value,
                            'created_at' => now()
                        ];
                    }
                    // Insert into MRO data history
                    BillMroDataHistory::insert($history_bulk);
                    // Helper file which accepts (api name, message, data/context)
                    ApiLogger::info('mro_api','mro_request_api','MRO batch created', [
                        'batch_id' => $batch_id,
                        'consumer_count' => $consumers->count(),
                    ]);
                    // Sending data to Call MRO Request API function.
                    return ['mro_bulk_data' => $mro_req_bulk, 'batch_id' => $batch_ar->id];
                }
            } 
            catch (\Throwable $e) {
                ApiLogger::error('mro_api','mro_request_api','MRO batch failed', [
                    'message' => $e->getMessage()
                ]);
            }
        }
        else{
            ApiLogger::info('mro_api','mro_request_api','No consumers found for MRO');
            return;
        }
    }

    public static function updateMroRequest($responses, $batch_id)
    {
        if($responses) {
            // $responses = Mro::request($api_data);
    
            foreach ($responses as $resp) {

                $mroData = BillMroData::where('mro_number', $resp['mro_order_id'])
                    ->where('mro_batch_id', $batch_id)
                    ->first();

                if ($mroData) {
                    $status = $resp['status'] === 'success' ? MroStatus::REQUESTED->value : MroStatus::REQUEST_ACK_FAIL->value;
                    $mroData->update([
                        'status_id' => $status,
                        'error_code'=> $resp['error_code'] ?? null,
                        'error_message'=> $resp['error_message'] ?? null,
                    ]);    
                    BillMroDataHistory::insert([
                        'mro_data_id' => $mroData->id,
                        'status_id'   => $status,
                        'created_at' => now()
                    ]);
                    ApiLogger::info('mro_api','mro_request_api', 'MRO record status updated', [
                        'mro_number' => $mroData->mro_number,
                        'status'     => $status,
                    ]);
                }
                else {
                    ApiLogger::warning('mro_api','mro_request_api', 'MRO record not found for response', [
                        'mro_order_id' => $resp['mro_order_id'],
                        'batch_id'     => $batch_id,
                    ]);
                }
            }
            print "Total MRO Requests generated : ".count($responses);
            ApiLogger::info('mro_api','mro_request_api','MRO API response received', [
                'batch_id' => $batch_id,
                'response_count' => count($responses)
            ]);
        }
        else {
            ApiLogger::info('mro_api','mro_request_api','No MRO data to send');
            return;
        }
    }
}