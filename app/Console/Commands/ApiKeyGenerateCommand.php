<?php

namespace App\Console\Commands;

use App\Models\Admin\APIKey;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ApiKeyGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:new-api-key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'New API Key generation process';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Generate API Key
        $key = 'mg_' . Str::random(40);

        APIKey::create([
            'name' => 'Polaris',
            'key' => hash('sha256', $key),
            'expires_at' => now()->addYear(),
        ]);
        
        echo $key;
    }
}
