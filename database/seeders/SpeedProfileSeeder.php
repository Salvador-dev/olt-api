<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SpeedProfile;

class SpeedProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $speeds = [["name" => "10Mb", "speed" => "10345"], ["name" => "30Mb", "speed" => "30565"], ["name" => "50Mb", "speed" => "50785"], ["name" => "60Mb", "speed" => "60345"], ["name" => "80Mb", "speed" => "80321"], ["name" => "100Mb", "speed" => "100345"], ["name" => "500Mb", "speed" => "500045"], ["name" => "1Gb", "speed" => "1049834"]];


        foreach ($speeds as $speed) {

            SpeedProfile::create([
                'name' => $speed["name"],
                'type_conexion' => "internet",
                'upload_speed' => $speed['speed'],
                'download_speed' => $speed['speed'],
            ]);
        }
        

    }
}
