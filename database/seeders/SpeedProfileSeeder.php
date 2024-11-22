<?php

namespace Database\Seeders;

use App\Jobs\SpeedProfileSeederJob;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SpeedProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SpeedProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $currentDB = DB::connection()->getDatabaseName();
        $id = explode('tenant', $currentDB)[1];

        SpeedProfileSeederJob::dispatch($id);

    }
}
