<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Signal;

class SignalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Signal::create(['description' => 'Very Good']);
        Signal::create(['description' => 'Warning']);
        Signal::create(['description' => 'Critical']);

  
    }
}
