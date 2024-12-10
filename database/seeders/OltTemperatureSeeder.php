<?php

namespace Database\Seeders;

use App\Jobs\OltTemperatureSeederJob;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OltTemperatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $currentDB = DB::connection()->getDatabaseName();
        $id = explode('tenant', $currentDB)[1];

        OltTemperatureSeederJob::dispatch($id);    
    
    }
}
