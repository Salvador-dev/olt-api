<?php

namespace Database\Factories;

use App\Models\Dummy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DummyFactory extends Factory
{

    protected $model = Dummy::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'unique_external_id'=> Str::random(10), 
            'sn'=> Str::random(10), 
            'olt_id'=> $this->faker->randomElement([18, 20]), 
            'onu_type_id'=> $this->faker->randomElement([2, 4, 5, 7]), 
            'zone_id'=> $this->faker->randomElement([49, 52]), 
            'status'=> $this->faker->randomElement(['Online', 'Offline']), 
            'name'=> $this->faker->name
        ];
    }
}
