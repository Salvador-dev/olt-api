<?php

namespace App\Console\Commands;

use App\Jobs\OltTemperatureSeederJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DataRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDB = DB::connection()->getDatabaseName();
        $id = explode('tenant', $currentDB)[1];

        OltTemperatureSeederJob::dispatch($id);    
    }
}
