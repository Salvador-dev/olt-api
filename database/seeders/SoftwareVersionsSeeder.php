<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SoftwareVersion;

class SoftwareVersionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Datos de ejemplo para insertar en la tabla software_versions
        $softwareVersionsData = [
            [
                'name' => '1212',
            ],
            [
                'name' => '1515',
            ],
        ];

        foreach ($softwareVersionsData as $data) {
            SoftwareVersion::create($data);
        }
    }
}
