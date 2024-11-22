<?php

namespace App\Jobs;

use App\Models\Onu;
use App\Models\ServicePort;
use App\Models\SpeedProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Facades\Tenancy;

class ServicePortSeederJob implements ShouldQueue
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

            \Illuminate\Support\Facades\Log::debug('======== SERVICE PORT SEEDER ========');
            \Illuminate\Support\Facades\Log::debug('ID ' . $this->id);
            \Illuminate\Support\Facades\Log::debug('TENANT ' . $tenant);
            \Illuminate\Support\Facades\Log::debug('CURRENT DB ' . $currentDB);

            $speedProfiles = SpeedProfile::all();

            foreach ($speedProfiles as $speedProfile) {
                
                ServicePort::create([
                    'speed_profile_id' => $speedProfile->id,
                    'onu_id' => Onu::inRandomOrder()->first()->id, 
                    'tag_mode' => 'translate', 

                ]);

            }
           
        });

    }
}
