<?php

namespace Database\Seeders;

use App\Jobs\DiagnosticSeederJob;
use App\Models\Diagnostic;
use App\Models\Onu;
use App\Models\Signal;
use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiagnosticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        $currentDB = DB::connection()->getDatabaseName();
        $id = explode('tenant', $currentDB)[1];

        DiagnosticSeederJob::dispatch($id);

    }
}
