<?php

namespace Database\Seeders;

use App\Jobs\OltSeederJob;
use App\Models\HardwareVersion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Olt;
use App\Models\PonType;
use App\Models\SoftwareVersion;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OltSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $currentDB = DB::connection()->getDatabaseName();
        $id = explode('tenant', $currentDB)[1];

        OltSeederJob::dispatch($id);
      
    }
}
