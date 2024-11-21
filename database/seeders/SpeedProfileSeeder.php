<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SpeedProfile;
use Illuminate\Support\Facades\Http;

class SpeedProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $speedProfiles = [];

        tenancy()->initialize('dell');

        try {

            $url = env('AUX_API_URL');

            $data = Http::withHeaders([
                'AK' => env('API_AUTH_KEY')
            ])->get($url . 'speed_profiles/listing');
            
            $data = $data->json()["data"];

            // optimizar y comparar tiempos
            
            foreach ($data as $data) {
                if(!in_array($data["name"], array_column($speedProfiles, "name"))){
                    array_push($speedProfiles, $data);
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
        

    }
}
