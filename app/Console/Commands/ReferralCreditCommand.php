<?php
namespace App\Console\Commands;

use App\Actions\ReferralCredit;
use App\Helpers\ApiLogger;
use Illuminate\Console\Command;

class ReferralCreditCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:referral-credit-command';

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
        $data = ReferralCredit::execute();
        ApiLogger::info('Referrals', 'refer-api', 'Total referral job result', $data);

    }
}