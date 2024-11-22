<?php

namespace App\Jobs;

use App\Models\Diagnostic;
use App\Models\Onu;
use App\Models\Signal;
use App\Models\Status;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Facades\Tenancy;

class DiagnosticSeederJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

    /**
     * Create a new job instance.
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        Tenancy::find($this->id)->run(function ($tenant) {

            $currentDB = DB::connection()->getDatabaseName();

            \Illuminate\Support\Facades\Log::debug('======== DIAGNOSTIC SEEDER ========');
            \Illuminate\Support\Facades\Log::debug('ID ' . $this->id);
            \Illuminate\Support\Facades\Log::debug('TENANT ' . $tenant);
            \Illuminate\Support\Facades\Log::debug('CURRENT DB ' . $currentDB);

            $onus = Onu::all();

            foreach ($onus as $onu) {
                
                $signal_value = number_format(rand(-5, -60), 2);

                $signal = Signal::where('max_frequency', '<=', $signal_value)->first();

                Diagnostic::create([
                    'signal_value' => $signal_value, 
                    'distance' => (string) rand(100, 6000), 
                    'onu_id' => $onu->id,
                    'status_id' => Status::inRandomOrder()->first()->id,
                    'signal_id' => $signal->signal_id,
                ]);
                
            }
           
        });

    }
}
