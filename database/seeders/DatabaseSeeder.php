<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AdministrativeStatus;
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

        $this->call(SpeedProfileSeeder::class);

        $this->call(ZoneSeeder::class);

        $this->call(OdbSeeder::class);

        $this->call(OltSeeder::class);

        $this->call(StatusSeeder::class);

        $this->call(AdministrativeStatusSeeder::class);
        
        $this->call(SignalSeeder::class);

        $this->call(OnuSeeder::class);

        $this->call(ServicePortSeeder::class);

        $this->call(ReportSeeder::class);

    }
}
