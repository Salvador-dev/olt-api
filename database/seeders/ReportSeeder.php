<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\Onu;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 100; $i++) {

            Report::create([
                'action' => Arr::random(['Authorized', 'ONU enabled', 'Reboot', 'CATV enabled', 'Download speed changed to -traffic-table_310', 'Upload speed changed to -traffic-table_310']),
                'onu_id' => Onu::inRandomOrder()->first()->id,
                'user_id' => Arr::random([User::inRandomOrder()->first()->id, null])
            ]);
        }
    }
}
