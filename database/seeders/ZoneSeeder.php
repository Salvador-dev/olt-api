<?php

namespace Database\Seeders;

use App\Jobs\ZoneSeederJob;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentDB = DB::connection()->getDatabaseName();
        $id = explode('tenant', $currentDB)[1];

        ZoneSeederJob::dispatch($id);
    }
    
}
