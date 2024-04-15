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

        Status::create(['description' => 'Online']);
        Status::create(['description' => 'Offline']);
        Status::create(['description' => 'Power fail']);
        Status::create(['description' => 'LOS']);
        
    }
}
