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
            
            ServicePort::create([
                'speed_profile_id' => $speedProfile->id,
                'onu_id' => Onu::inRandomOrder()->first()->id, 
                'tag_mode' => 'translate', 

            ]);

        }

    }
}
