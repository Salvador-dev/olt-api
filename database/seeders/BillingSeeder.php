<?php

namespace Database\Seeders;

use App\Jobs\BillingSeederJob;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BillingSeeder extends Seeder
{
    /**
     * Create a new seeder instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $currentDB = DB::connection()->getDatabaseName();
        $id = explode('tenant', $currentDB)[1];

        BillingSeederJob::dispatch($id);

    }
}
