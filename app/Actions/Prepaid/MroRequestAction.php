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
        $consumers = Consumer::selectRaw('cns_consumers.id')
            ->join('cns_prepaid', 'cns_consumers.id', '=', 'cns_prepaid.consumer_id')
            ->leftJoin('bil_mro_data', function($join) use($latestMro) {
                $join->on('bil_mro_data.consumer_id', '=', 'cns_consumers.id')
                ->where('bil_mro_data.created_at', $latestMro)
                ->whereIn('bil_mro_data.status_id', [1, 2, 3]);
            })
            ->whereDate('cns_prepaid.hes_date', '<=', $req_start_date)
            ->whereNull('bil_mro_data.id')
            ->get();
        dd($consumers);

        // Create Batch ID with Str uuid and insert in bulk
        $batch_id = Str::uuid();
        // Prepare bulk insert array along with API input
        $mro_data_bulk = [];
        $mro_req_bulk = [];
        foreach($consumers as $consumer) {
            $mro_data_bulk[] = [
                'consumer_id' => $consumer->id,
                'mro_number' => '',
                'schedule_date' => $schedule_date,
                'status_id' => 1,
                'batch_id' => $batch_id,
            ];
            // API Array
            $mro_req_bulk[] = [
                'mro_order_id' => '',
                'mech_meter_serial_number' => $consumer->activeMeter->meter_no,
                'prepaid_mod_number' => $consumer->activeMeter->meter_serial_no,
                'scheduled_mr_date' => $schedule_date,
                'crn' => $consumer->crn
            ];
        }
        // Insert into MRO data
        $mro_data_batch_insert = BillMroData::insert($mro_data_bulk);
        // Insert into MRO data history
        $mro_history_batch_insert = BillMroDataHistory::insertUsing(
            ['mro_data_id', 'status_id', 'created_at'],
            BillMroData::select('id', 1, DB::raw('now()'))->where('batch_id', $batch_id)
        );

        // Send to HES
        $mro_request = Mro::request($mro_req_bulk);
    }
}