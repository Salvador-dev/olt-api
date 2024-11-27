<?php

namespace Database\Seeders;

use App\Jobs\OnuTypeSeederJob;
use App\Models\Capability;
use App\Models\OnuType;
use App\Models\PonType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class OnuTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $currentDB = DB::connection()->getDatabaseName();
        $id = explode('tenant', $currentDB)[1];

        OnuTypeSeederJob::dispatch($id);
    }
}
