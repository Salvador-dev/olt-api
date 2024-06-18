<?php

namespace Database\Seeders;

use App\Models\AdministrativeStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdministrativeStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdministrativeStatus::create(['description' => 'Enabled', 'status_id' => 1]);
        AdministrativeStatus::create(['description' => 'Disabled', 'status_id' => 0]);

    }
}
