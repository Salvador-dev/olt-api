<?php

namespace App\Jobs;

use App\Models\Zone;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Stancl\Tenancy\Facades\Tenancy;

class ZoneSeederJob implements ShouldQueue
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

            \Illuminate\Support\Facades\Log::debug('======== ZONES SEEDER ========');
            \Illuminate\Support\Facades\Log::debug('ID ' . $this->id);
            \Illuminate\Support\Facades\Log::debug('TENANT ' . $tenant);
            \Illuminate\Support\Facades\Log::debug('CURRENT DB ' . $currentDB);

            $zonesData = [];

            try {
    
                $url = env('AUX_API_URL');
    
                $data = Http::withHeaders([
                    'AK' => env('API_AUTH_KEY')
                ])->get($url . 'zones/listing');     
    

                if(!$data->json()["status"]){

                    $zonesData = [['name' => 'Zona 1'], ['name' => 'Zona 1'], ['name' => 'Zona 1'], ['name' => 'Zona 1']];

                } else {

                    // optimizar y comparar tiempos
                
                    $zonesData = $data->json()["data"];

                }
                   
            } catch (\Throwable $th) {
                \Illuminate\Support\Facades\Log::debug('paso algo');
                \Illuminate\Support\Facades\Log::debug($th);
    
                $zonesData = [['name' => 'Zona 1'], ['name' => 'Zona 1'], ['name' => 'Zona 1'], ['name' => 'Zona 1']];
            }
    
            foreach ($zonesData as $data) {
                Zone::create(['name' => $data["name"], 'smart_olt_id' => $data["id"] ?? null]);
            }
           
        });
    }
}
