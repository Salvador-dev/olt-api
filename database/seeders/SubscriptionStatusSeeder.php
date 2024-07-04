<?php

namespace Database\Seeders;

use App\Models\SubscriptionStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubscriptionStatus::create(['description' => 'Active', 'status_id' => 1]);
        SubscriptionStatus::create(['description' => 'Trial', 'status_id' => 2]);
        SubscriptionStatus::create(['description' => 'Processing', 'status_id' => 3]);
        SubscriptionStatus::create(['description' => 'Expired', 'status_id' => 0]);
    }
}
