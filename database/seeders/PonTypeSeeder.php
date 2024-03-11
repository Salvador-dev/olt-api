<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PonType;

class PonTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PonType::create(['name' => 'GPON']);
        PonType::create(['name' => 'EPON']);
        PonType::create(['name' => 'GPON | EPON']);
    }
}
