<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminDataSeeder::class);
        $this->call(ModuleDataSeeder::class);
        $this->call(MasterDataSeeder::class);
        $this->call(MasterConsumerDataSeeder::class);
        $this->call(MasterInvoiceDataSeeder::class);
        $this->call(SpotDataSeeder::class);
        // $this->call(TestDataSeeder::class);
    }
}
