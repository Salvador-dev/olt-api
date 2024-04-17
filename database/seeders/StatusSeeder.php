<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Status::create(['description' => 'Online', 'status_id' => 1]);
        Status::create(['description' => 'Offline', 'status_id' => 2]);
        Status::create(['description' => 'Power fail', 'status_id' => 3]);
        Status::create(['description' => 'LOS', 'status_id' => 4]);
        Status::create(['description' => 'Unknown', 'status_id' => 5]);

    }
}
