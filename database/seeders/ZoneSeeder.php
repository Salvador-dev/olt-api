<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Zone;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Zone::create(['name' => 'Zona 1']);
        Zone::create(['name' => 'Zona 2']);
        Zone::create(['name' => 'Zona 3']);
        Zone::create(['name' => 'Zona 4']);
        Zone::create(['name' => 'Zona 5']);
        Zone::create(['name' => 'Zona 6']);

    }
}
