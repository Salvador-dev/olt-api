<?php

namespace App\Jobs;

use App\Models\SpeedProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Stancl\Tenancy\Facades\Tenancy;

class SpeedProfileSeederJob implements ShouldQueue
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

            \Illuminate\Support\Facades\Log::debug('======== SPEED PROFILE SEEDER ========');
            \Illuminate\Support\Facades\Log::debug('ID ' . $this->id);
            \Illuminate\Support\Facades\Log::debug('TENANT ' . $tenant);
            \Illuminate\Support\Facades\Log::debug('CURRENT DB ' . $currentDB);

            $speedProfiles = [];

            try {
    
                $url = env('AUX_API_URL');
    
                $data = Http::withHeaders([
                    'AK' => env('API_AUTH_KEY')
                ])->get($url . 'speed_profiles/listing');
                
                if(!$data->json()["status"]){

                    $speedProfiles = [["name" => "10Mb", "speed" => "10345", "type" => "internet"], ["name" => "30Mb", "speed" => "30565", "type" => "internet"], ["name" => "50Mb", "speed" => "50785", "type" => "internet"], ["name" => "60Mb", "speed" => "60345", "type" => "internet"], ["name" => "80Mb", "speed" => "80321", "type" => "internet"], ["name" => "100Mb", "speed" => "100345", "type" => "internet"], ["name" => "500Mb", "speed" => "500045", "type" => "internet"], ["name" => "1Gb", "speed" => "1049834", "type" => "internet"]];

                } else {

                    // optimizar y comparar tiempos
                
                    $data = $data->json()["data"];

                    // optimizar y comparar tiempos
                
                    foreach ($data as $data) {
                        if(!in_array($data["name"], array_column($speedProfiles, "name"))){
                            array_push($speedProfiles, $data);
                        }
                    }

                }
                   
            } catch (\Throwable $th) {
    
                \Illuminate\Support\Facades\Log::debug('paso algo');
                \Illuminate\Support\Facades\Log::debug($th);
    
                $speedProfiles = [["name" => "10Mb", "speed" => "10345", "type" => "internet"], ["name" => "30Mb", "speed" => "30565", "type" => "internet"], ["name" => "50Mb", "speed" => "50785", "type" => "internet"], ["name" => "60Mb", "speed" => "60345", "type" => "internet"], ["name" => "80Mb", "speed" => "80321", "type" => "internet"], ["name" => "100Mb", "speed" => "100345", "type" => "internet"], ["name" => "500Mb", "speed" => "500045", "type" => "internet"], ["name" => "1Gb", "speed" => "1049834", "type" => "internet"]];
            }
    
            foreach ($speedProfiles as $speed) {
    
                SpeedProfile::create([
                    'name' => $speed["name"],
                    'type_conexion' => $speed['type'],
                    'upload_speed' => $speed['speed'],
                    'download_speed' => $speed['speed'],
                ]);
            }
           
        });

    }
}
