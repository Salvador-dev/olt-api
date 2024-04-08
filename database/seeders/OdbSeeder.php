<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Odb;
use App\Models\Zone;
use Illuminate\Support\Arr;

class OdbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coordinates = [['latitude' => '10.49810811680425', 'longitude' => '-426.90862655639654'], ['latitude' => '10.493550809271058', 'longitude' => '-426.859359741211'], ['latitude' => '10.590421288241636', 'longitude' => '-426.98913574218756'], ['latitude' => '10.455401826918397', 'longitude' => '-426.6293334960938']];

        for ($i = 1; $i <= 10; $i++) {

            $randomData =  Arr::random($coordinates);

            Odb::create([
                'name' => 'ODB ' . $i,
                'nr_of_ports' => (string) rand(1, 5),
                'latitude' => $randomData["latitude"],
                'longitude' => $randomData["longitude"],
                'zone_id' => Zone::inRandomOrder()->first()->id
            ]);
        }
    }
}
