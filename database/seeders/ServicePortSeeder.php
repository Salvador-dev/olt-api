<?php

namespace Database\Seeders;

use App\Models\Onu;
use App\Models\ServicePort;
use App\Models\SpeedProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicePortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $speedProfiles = SpeedProfile::all();

        foreach ($speedProfiles as $speedProfile) {

            $downloadProfile = $speedProfiles->where('speed', $speedProfile->speed)->where('direction', 'download')->first();

            $uploadProfile = $speedProfiles->where('speed', $speedProfile->speed)->where('direction', 'upload')->first();
            
            ServicePort::create([
                'download_speed_id' => $downloadProfile->id,
                'up_speed_id' => $uploadProfile->id,
                'onu_id' => Onu::inRandomOrder()->first()->id, 
                'tag_mode' => 'translate', 

            ]);

        }

    }
}
