<?php

namespace App\Console\Commands;

use App\Actions\Prepaid\MroProcessAction;
use App\Contracts\Prepaid\Mro;
use App\Enums\PrepaidApi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MroPreocessCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mro-process-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Initiate the Command to fetch the unprocessed data.
        $data = MroProcessAction::getBillingData();
        if($data) {
            if($data['ack_payload']){
                // Call Mro Acknowledgment API.
                $responses = Mro::request($data['ack_payload'], PrepaidApi::mroAcknowledgment()->value);
                // Send the API response to the update function.
                MroProcessAction::updateMroRequest($responses);
            }
            // if($data['ack_fail_payload']) {
            //     // Call Mro Acknowledgment API for failure data.
            //     $responses = Mro::request($data['ack_fail_payload'], PrepaidApi::mroAcknowledgment()->value);
            //     // Send the API response to the update function.
            //     MroProcessAction::updateMroRequest($responses);
            // }
        }
        else {
            Log::error("No Mro Request Data.");
        }
    }
}
