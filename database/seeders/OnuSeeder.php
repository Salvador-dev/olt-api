<?php

namespace Database\Seeders;

use App\Models\Onu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OnuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Onu::factory(1000)->create();
    }
}
