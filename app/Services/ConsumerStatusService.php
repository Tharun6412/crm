<?php

namespace App\Services;

use App\Models\Consumer\TeamConsumer;
use Illuminate\Support\Facades\Auth;

class ConsumerStatusService
{
    /**
     * Create invoice
     * 
     * @param array $ConsumerData
     */
    public static function update($consumer_id, $status_id)
    {
        // Target Status from Team Consumer
        $consumer_target_data = TeamConsumer::where('consumer_id', $consumer_id)->where('status_id', $status_id)->first();
        if($consumer_target_data) {
            $consumer_target_data->update([
                'status' => 1, //1 = completed
            ]);
        }
        return true;
    }
}