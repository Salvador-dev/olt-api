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
        $softwareVersionsData = [ ['name' => 'R018'], ['name' => 'R015'], ['name' => 'R011'], ['name' => 'R017'], ['name' => 'R019'], ['name' => '2.x'], ['name' => 'R008'], ['name' => 'R013'] ];

        foreach ($softwareVersionsData as $data) {
            SoftwareVersion::create($data);
        }
    }
}
