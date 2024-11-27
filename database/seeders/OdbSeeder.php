<?php

namespace Database\Seeders;

use App\Jobs\OdbSeederJob;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Odb;
use App\Models\Zone;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class OdbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $currentDB = DB::connection()->getDatabaseName();
        $id = explode('tenant', $currentDB)[1];

        OdbSeederJob::dispatch($id);
    
    }
}
