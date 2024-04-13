<?php

namespace Database\Factories;

use App\Models\AdministrativeStatus;
use App\Models\Odb;
use App\Models\Olt;
use App\Models\Status;
use App\Models\Signal;
use App\Models\OnuType;
use App\Models\SpeedProfile;
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
            'status_id' => Status::inRandomOrder()->first()->id, 
            'name' => Str::random(7) . '-' . $this->faker->name,
            'signal_id' => Signal::inRandomOrder()->first()->id,
            'speed_profile_id' => SpeedProfile::inRandomOrder()->first()->id,
            'board' => (string) rand(1, 20),
            'port' => (string) rand(1, 15),
            'administrative_status' => AdministrativeStatus::inRandomOrder()->first()->id
        ];
    }
}

