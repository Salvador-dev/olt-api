<?php

namespace Database\Seeders;

use App\Jobs\ServicePortSeederJob;
use App\Models\Onu;
use App\Models\ServicePort;
use App\Models\SpeedProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicePortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        
        $currentDB = DB::connection()->getDatabaseName();
        $id = explode('tenant', $currentDB)[1];

       ServicePortSeederJob::dispatch($id);
       
    }
}
