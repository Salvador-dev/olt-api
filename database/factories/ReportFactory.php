<?php

namespace Database\Factories;

use App\Models\Onu;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'action' => Arr::random(['Authorized', 'ONU enabled', 'Reboot', 'CATV enabled', 'Authorized', 'Authorized', 'Authorized',]),
            'onu_id' => Onu::inRandomOrder()->first()->id,
            'user_id' => Arr::random([User::inRandomOrder()->first()->id, null]),
            'created_at' => $this->faker->dateTimeBetween($startDate = '-2 month', $endDate = 'now')
        ];
    }
}