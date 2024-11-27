<?php

namespace App\Jobs;

use App\Models\Capability;
use App\Models\OnuType;
use App\Models\PonType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Stancl\Tenancy\Facades\Tenancy;

class OnuTypeSeederJob implements ShouldQueue
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

            \Illuminate\Support\Facades\Log::debug('======== ONU TYPES SEEDER ========');
            \Illuminate\Support\Facades\Log::debug('ID ' . $this->id);
            \Illuminate\Support\Facades\Log::debug('TENANT ' . $tenant);
            \Illuminate\Support\Facades\Log::debug('CURRENT DB ' . $currentDB);

            $onuTypesData = [];

            try {
    
                $url = env('AUX_API_URL');
    
                $data = Http::withHeaders([
                    'AK' => env('API_AUTH_KEY')
                ])->get($url . 'onu_types/listing');     
    

                if(!$data->json()["status"]){

                    $onuTypesData = [[ "name" => "V2804AX", "pon_type" => "gpon", "capability" => "Bridging/Routing", "ethernet_ports" => 24, "wifi_ports" => 0, "voip_ports" => 24, "catv" => "0", "allow_custom_profiles" => "1"], [ "name" => "XZ000-G3", "pon_type" => "gpon", "capability" => "Bridging/Routing", "ethernet_ports" => 24, "wifi_ports" => 0, "voip_ports" => 24, "catv" => "0", "allow_custom_profiles" => "1"], [ "name" => "ZC-521G", "pon_type" => "gpon", "capability" => "Bridging/Routing", "ethernet_ports" => 24, "wifi_ports" => 0, "voip_ports" => 24, "catv" => "0", "allow_custom_profiles" => "1"],[ "name" => "ZTE-F600", "pon_type" => "gpon", "capability" => "Bridging/Routing", "ethernet_ports" => 24, "wifi_ports" => 0, "voip_ports" => 24, "catv" => "0", "allow_custom_profiles" => "1"], [ "name" => "ZTE-F620", "pon_type" => "gpon", "capability" => "Bridging/Routing", "ethernet_ports" => 24, "wifi_ports" => 0, "voip_ports" => 24, "catv" => "0", "allow_custom_profiles" => "1"]];

                } else {

                    // optimizar y comparar tiempos
                
                    $onuTypesData = $data->json()["data"];

                }
                   
            } catch (\Throwable $th) {
                \Illuminate\Support\Facades\Log::debug('paso algo');
                \Illuminate\Support\Facades\Log::debug($th);
    
                $onuTypesData = [[ "name" => "V2804AX", "pon_type" => "gpon", "capability" => "Bridging/Routing", "ethernet_ports" => 24, "wifi_ports" => 0, "voip_ports" => 24, "catv" => "0", "allow_custom_profiles" => "1"], [ "name" => "XZ000-G3", "pon_type" => "gpon", "capability" => "Bridging/Routing", "ethernet_ports" => 24, "wifi_ports" => 0, "voip_ports" => 24, "catv" => "0", "allow_custom_profiles" => "1"], [ "name" => "ZC-521G", "pon_type" => "gpon", "capability" => "Bridging/Routing", "ethernet_ports" => 24, "wifi_ports" => 0, "voip_ports" => 24, "catv" => "0", "allow_custom_profiles" => "1"],[ "name" => "ZTE-F600", "pon_type" => "gpon", "capability" => "Bridging/Routing", "ethernet_ports" => 24, "wifi_ports" => 0, "voip_ports" => 24, "catv" => "0", "allow_custom_profiles" => "1"], [ "name" => "ZTE-F620", "pon_type" => "gpon", "capability" => "Bridging/Routing", "ethernet_ports" => 24, "wifi_ports" => 0, "voip_ports" => 24, "catv" => "0", "allow_custom_profiles" => "1"]];
            }
    
            foreach ($onuTypesData as $data) {
                
                OnuType::create([
                    'name' => $data["name"],
                    'pon_type_id' => PonType::where("name", $data["pon_type"])->first()->id,
                    'capability_id' => Capability::where("name", $data["capability"])->first()->id,
                    'allow_custom_profiles' => $data["allow_custom_profiles"] == "1" ? true : false,
                    'ethernet_ports' => $data["ethernet_ports"], 
                    'wifi_ports' => $data["wifi_ports"], 
                    'voip_ports' => $data["voip_ports"], 
                    'catv' => $data["catv"] == "1" ? true : false,
                ]);
            }
           
        });
    }
}
