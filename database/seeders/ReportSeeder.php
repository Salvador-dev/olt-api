<?php

namespace Database\Seeders;

use App\Jobs\ReportSeederJob;
use App\Models\Report;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $currentDB = DB::connection()->getDatabaseName();
        $id = explode('tenant', $currentDB)[1];
        
        ReportSeederJob::dispatch($id);

    }
}
