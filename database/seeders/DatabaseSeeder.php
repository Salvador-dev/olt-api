<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\OnuType;
use App\Models\ServicePort;
use App\Models\SpeedProfile;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $this->call(RoleSeeder::class);

        $this->call(UserSeeder::class);

        $this->call(HardwareVersionSeeder::class);

        $this->call(SoftwareVersionsSeeder::class);

        $this->call(CapabilitySeeder::class);

        $this->call(PonTypeSeeder::class);

        $this->call(OnuTypeSeeder::class);

        $this->call(SpeedProfile::class);

        $this->call(ServicePort::class);

        $this->call(ZoneSeeder::class);

        $this->call(OltSeeder::class);

        $this->call(OnuSeeder::class);


    }
}
