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
        AdministrativeStatus::create(['description' => 'Enabled']);
        AdministrativeStatus::create(['description' => 'Disabled']);

    }
}
