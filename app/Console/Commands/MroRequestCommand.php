<?php

namespace App\Console\Commands;

use App\Actions\Prepaid\MroRequestAction;
use App\Contracts\Prepaid\Mro;
use App\Enums\PrepaidApi;
use Illuminate\Console\Command;

class MroRequestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mro-request-command';

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
        // Call the MRO request action.
        $data = MroRequestAction::getConsumer();
        if($data) {
            // Call Mro Request API.
            $responses = Mro::request($data['mro_bulk_data'], PrepaidApi::mroRequest()->value);
            // Send the API response to the update function.
            MroRequestAction::updateMroRequest($responses, $data['batch_id']);
        }
        else {
            logger()->error("No Mro Request Data.");
        }
    }
}
