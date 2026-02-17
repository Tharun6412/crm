<?php

namespace App\Console\Commands;

use App\Actions\Prepaid\MroRequestAction;
use Illuminate\Console\Command;

class MroRequestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mro-preocess-command';

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
        $action = MroRequestAction::getConsumer();
    }
}
