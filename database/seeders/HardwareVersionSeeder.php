<?php

namespace Database\Seeders;

use App\Jobs\HardwareVersionSeederJob;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HardwareVersion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class HardwareVersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $currentDB = DB::connection()->getDatabaseName();
        $id = explode('tenant', $currentDB)[1];
        
        HardwareVersionSeederJob::dispatch($id);
        
    }
}
