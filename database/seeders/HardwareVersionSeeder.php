<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HardwareVersion;


class HardwareVersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $softwareVersionsData = ['Huawei-MA5800-X7', 'Huawei-MA5800-X15', 'Huawei-MA5680T', 'Huawei-MA5603','Huawei-MA5600','Huawei-MA5608T', 'ZTE-C300', 'Huawei-MA5800-X17', 'ZTE-C320', 'Huawei-MA5600T'];

        foreach ($softwareVersionsData as $data) {
            HardwareVersion::create(['name' => $data]);
        }
    }
}
