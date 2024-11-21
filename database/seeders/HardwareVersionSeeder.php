<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HardwareVersion;
use Illuminate\Support\Facades\Http;

class HardwareVersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hardwareVersionsData = [];

        try {

            $url = env('AUX_API_URL');

            $data = Http::withHeaders([
                'AK' => env('API_AUTH_KEY')
            ])->get($url . 'olts/listing');
            
            $data = $data->json()["data"];

            // optimizar y comparar tiempos
            
            foreach ($data as $data) {
                if(!in_array($data["olt_hardware_version"], $hardwareVersionsData)){
                    array_push($hardwareVersionsData, $data["olt_hardware_version"]);
                }
            }
            
               
        } catch (\Throwable $th) {

            \Illuminate\Support\Facades\Log::debug('paso algo');
            \Illuminate\Support\Facades\Log::debug($th);

            $hardwareVersionsData = ['Huawei-MA5800-X7', 'Huawei-MA5800-X15', 'Huawei-MA5680T', 'Huawei-MA5603','Huawei-MA5600', 'ZTE-C300', 'Huawei-MA5800-X17', 'ZTE-C320', 'Huawei-MA5600T'];
        }

        foreach ($hardwareVersionsData as $data) {
            HardwareVersion::create(['name' => $data]);
        }
    }
}
