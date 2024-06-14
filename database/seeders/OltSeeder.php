<?php

namespace Database\Seeders;

use App\Models\HardwareVersion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Olt;
use App\Models\PonType;
use App\Models\SoftwareVersion;
use Illuminate\Support\Arr;

class OltSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $oltData = [['name' => 'OLT-BARINAS', 'ip' => '190.97.236.254'], ['name' => 'OLT-HUAWEI-CIUDAD-ALIANZA', 'ip' => '190.103.30.76'], ['name' => 'OLT-HUAWEI-SAN-DIEGO', 'ip' => '190.120.253.220'], ['name' => 'OLT-HUAWEI-UNICENTER', 'ip' => '190.103.31.160'], ['name' => 'OLT-HUAWEI-PARAISO', 'ip' => '190.89.29.37'], ['name' => 'OLT-HUAWEI-BARCELONA', 'ip' => '190.97.236.254']];

        foreach ($oltData as $data) {
            Olt::create([
                'name' => $data["name"],
                'ip' => $data["ip"],
                'olt_hardware_version_id' => HardwareVersion::inRandomOrder()->first()->id,
                'pon_type_id' => PonType::inRandomOrder()->first()->id,
                'olt_software_version_id' => SoftwareVersion::inRandomOrder()->first()->id,
            ]);
        }

    }
}
