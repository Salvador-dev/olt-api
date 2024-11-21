<?php

namespace Database\Seeders;

use App\Models\HardwareVersion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Olt;
use App\Models\PonType;
use App\Models\SoftwareVersion;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class OltSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $oltData = [];

        try {

            $url = env('AUX_API_URL');

            $data = Http::withHeaders([
                'AK' => env('API_AUTH_KEY')
            ])->get($url . 'olts/listing');     

            $oltData = $data->json()["data"];
               
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
                'olt_active' => 0,
                'olt_hardware_version_id' => HardwareVersion::where("name", $data["olt_hardware_version"])->first()->id,
                'pon_type_id' => PonType::inRandomOrder()->first()->id,
                'olt_software_version_id' => SoftwareVersion::inRandomOrder()->first()->id,
                'snmp_udp_port' => intval($data["snmp_port"]),
                'telnet_port' => intval($data["telnet_port"]),
            ]);
        }

    }
}
