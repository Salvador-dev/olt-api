<?php

namespace App\Jobs;

use App\Models\AdministrativeStatus;
use App\Models\Olt;
use App\Models\Onu;
use App\Models\OnuType;
use App\Models\PonPort;
use App\Models\ServicePort;
use App\Models\SpeedProfile;
use App\Models\Zone;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Stancl\Tenancy\Facades\Tenancy;

class OltConfiguredOnusById implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id, $oltId;

    /**
     * Create a new job instance.
     */
    public function __construct($id, $oltId)
    {
        $this->id = $id;
        $this->oltId = $oltId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Tenancy::find($this->id)->run(function ($tenant) {

            $currentDB = DB::connection()->getDatabaseName();

            \Illuminate\Support\Facades\Log::debug('======== OLT CONF ONUS JOB ========');
            \Illuminate\Support\Facades\Log::debug('ID ' . $this->id);
            \Illuminate\Support\Facades\Log::debug('TENANT ' . $tenant);
            \Illuminate\Support\Facades\Log::debug('CURRENT DB ' . $currentDB);
       
            $olt = Olt::with('pon_ports')->select('id', 'smart_olt_id')->where('smart_olt_id', '!=', null)->where('id', $this->oltId)->first();
            $onusData = [];

            if($olt->smart_olt_id != null){

                $url = env('AUX_API_URL');

                if(count($olt->pon_ports) > 0){

                    foreach ($olt->pon_ports as $pon_port) {

                        try {
                            $response = Http::retry(3, 500)->timeout(60)->withHeaders([
                                'AK' => env('API_AUTH_KEY')
                            ])->get($url . 'onus/configured_onus_for_olt/' . $olt->smart_olt_id . '/' . $pon_port->board . '/' . $pon_port->pon_port); 
                
                            if($response->json()["status"] && count($response->json()["data"]) > 0){
            
                                $data = $response->json()["data"];
            
                                foreach ($data as $onu) {
                                    array_push($onusData, $onu);
                                }
            
                            } 
                        } catch (\Throwable $th) {
                            $olt->olt_active = 0;
                            \Illuminate\Support\Facades\Log::debug('paso algo');
                            \Illuminate\Support\Facades\Log::debug($th);
                        }
                    }
                }

            }
            
            if(count($onusData) > 0){

                foreach ($onusData as $data) {

                    $onu = Onu::updateOrCreate([
                        'olt_id' => $olt->id, 
                        'unique_external_id' => $data["unique_external_id"] ?? "no tiene", 
                        'board' => $data["board"],
                        'port' => $data["port"],
                    ],
                    [
                        'serial' => $data["sn"] ?? "no tiene", 
                        'onu_type_id' => OnuType::where('smart_olt_id', $data["onu_type_id"])->first()->id ?? OnuType::inRandomOrder()->first()->id, 
                        'zone_id' => Zone::where('name', $data["zone_name"])->first()->id, 
                        'name' => $data["name"],
                        'administrative_status_id' => AdministrativeStatus::where('description', $data['administrative_status'])->first()->status_id,
                        'wan_mode' => $data['wan_mode'],
                        'address' => $data['address'],
                        'catv' => $data['catv'],
                        'speed_profile_id' => SpeedProfile::where('name', $data['service_ports'][0]["upload_speed"])->first()->id, 

                    ]);
                    
                    if(count($data['service_ports']) > 0){

                        foreach ($data['service_ports'] as $service_port) {

                            ServicePort::updateOrCreate([
                                'onu_id' => $onu->id, 
                            ],
                            [
                                'tag_mode' => $service_port["tag_transform_mode"], 
                                'speed_profile_id' => SpeedProfile::where('name', $service_port["upload_speed"])->first()->id, 
                            ]);
                        }

                    }
                    
                }

                $olt->olt_active = 1;
            }

            $olt->save();

        });
    }
}
