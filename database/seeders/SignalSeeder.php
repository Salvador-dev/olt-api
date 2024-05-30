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
        Signal::create(['description' => 'Very Good', 'signal_id' => 1, 'max_frequency' => -25.00]);
        Signal::create(['description' => 'Warning', 'signal_id' => 2, 'max_frequency' => -31.00]);
        Signal::create(['description' => 'Critical', 'signal_id' => 3, 'max_frequency' => -60.00]);
        Signal::create(['description' => 'Unknown', 'signal_id' => 4]);
  
    }
}
