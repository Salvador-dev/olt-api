<?php

namespace Database\Seeders;

use App\Models\Capability;
use App\Models\OnuType;
use App\Models\PonType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class OnuTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $array1 = ["yes", "no"];
        $onuTypeNames = ["1126", "ACL612V6.0", "EG8141A5", "FD502GWD", "HG8010H", "MONUV601", "ONT4GE2WZ", "ONU-type-eth-2-pots-2-catv-1", "SY2200", "UFiber-Nano", "V2804AX", "XZ000-G3", "ZC-521G", "ZTE-F600", "ZTE-F620"];

        foreach ($onuTypeNames as $data) {
            OnuType::create([
                'name' => $data,
                'pon_type_id' => PonType::inRandomOrder()->first()->id,
                'capability_id' => Capability::inRandomOrder()->first()->id,
                'allow_custom_profiles' => Arr::random($array1),
            ]);
        }
    }
}
