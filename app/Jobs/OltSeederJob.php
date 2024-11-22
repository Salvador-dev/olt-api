<?php

namespace App\Jobs;

use App\Models\HardwareVersion;
use App\Models\Olt;
use App\Models\PonType;
use App\Models\SoftwareVersion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Stancl\Tenancy\Facades\Tenancy;

class OltSeederJob implements ShouldQueue
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

            \Illuminate\Support\Facades\Log::debug('======== OLT SEEDER ========');
            \Illuminate\Support\Facades\Log::debug('ID ' . $this->id);
            \Illuminate\Support\Facades\Log::debug('TENANT ' . $tenant);
            \Illuminate\Support\Facades\Log::debug('CURRENT DB ' . $currentDB);

            $oltData = [];

            try {
    
                $url = env('AUX_API_URL');
    
                $data = Http::withHeaders([
                    'AK' => env('API_AUTH_KEY')
                ])->get($url . 'olts/listing');     
    

                if(!$data->json()["status"]){

                    $oltData = [
                        ['name' => 'OLT-HUAWEI-UNICENTER', 'ip' => '172.29.0.2', "olt_hardware_version" => "Huawei-MA5680T", "telnet_port"=> "2335", "snmp_port"=> "2163"], 
                        ['name' => 'OLT-HUAWEI-BARQUISIMETO-OESTE-I(PRADO)', 'ip' => '190.120.252.184', "olt_hardware_version" => "Huawei-MA5680T", "telnet_port"=> "2335", "snmp_port"=> "2163"], 
                        ['name' => 'OLT-HUAWEI-FLOR-AMARILLO', 'ip' => '190.120.253.215', "olt_hardware_version" => "Huawei-MA5680T", "telnet_port"=> "2335", "snmp_port"=> "2163"]
                    ];

                } else {

                    // optimizar y comparar tiempos
                
                    $oltData = $data->json()["data"];

                }
                   
            } catch (\Throwable $th) {
                \Illuminate\Support\Facades\Log::debug('paso algo');
                \Illuminate\Support\Facades\Log::debug($th);
    
                $oltData = [
                    ['name' => 'OLT-HUAWEI-UNICENTER', 'ip' => '172.29.0.2', "olt_hardware_version" => "Huawei-MA5680T", "telnet_port"=> "2335", "snmp_port"=> "2163"], 
                    ['name' => 'OLT-HUAWEI-BARQUISIMETO-OESTE-I(PRADO)', 'ip' => '190.120.252.184', "olt_hardware_version" => "Huawei-MA5680T", "telnet_port"=> "2335", "snmp_port"=> "2163"], 
                    ['name' => 'OLT-HUAWEI-FLOR-AMARILLO', 'ip' => '190.120.253.215', "olt_hardware_version" => "Huawei-MA5680T", "telnet_port"=> "2335", "snmp_port"=> "2163"]
                ];
            }
    
            foreach ($oltData as $data) {
                Olt::create([
                    'name' => $data["name"],
                    'ip' => $data["ip"],
                    'olt_active' => 1,
                    'olt_hardware_version_id' => HardwareVersion::where("name", $data["olt_hardware_version"])->first()->id,
                    'pon_type_id' => PonType::inRandomOrder()->first()->id,
                    'olt_software_version_id' => SoftwareVersion::inRandomOrder()->first()->id,
                    'snmp_udp_port' => intval($data["snmp_port"]),
                    'telnet_port' => intval($data["telnet_port"]),
                ]);
            }
           
        });

    }
}
