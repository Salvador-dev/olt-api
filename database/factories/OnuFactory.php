<?php

namespace Database\Factories;

use App\Models\Odb;
use App\Models\Olt;
use App\Models\Onu;
use App\Models\OnuType;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Onu>
 */
class OnuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'unique_external_id' => Str::random(10), 
            'serial' => Str::random(10), 
            'olt_id' => Olt::inRandomOrder()->first()->id, 
            'onu_type_id' => OnuType::inRandomOrder()->first()->id, 
            'zone_id' => Zone::inRandomOrder()->first()->id, 
            'odb_id' => Odb::inRandomOrder()->first()->id,
            'status' => $this->faker->randomElement(['Online', 'Offline', 'Power fail', 'LOS']), 
            'name' => Str::random(7) . '-' . $this->faker->name,
            'signal' => $this->faker->randomElement(['Very good', 'Warning', 'Critical']),
            'administrative_status' => $this->faker->randomElement(['Enabled', 'Disabled'])
        ];
    }
}

