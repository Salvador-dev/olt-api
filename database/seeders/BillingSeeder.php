<?php

namespace Database\Seeders;

use App\Models\Billing;
use App\Models\BillingHistory;
use App\Models\Olt;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class BillingSeeder extends Seeder
{

       /**
     * The current Faker instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Create a new seeder instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->faker = $this->withFaker();
    }

    /**
     * Get a new Faker instance.
     *
     * @return \Faker\Generator
     */
    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $olts = Olt::all();

        $oltData = [
            ['subscription_end_date' => $this->faker->dateTimeBetween($startDate = '-2 months', $endDate = 'now'), 'subscription_status_id' => 0], 
            ['subscription_end_date' => $this->faker->dateTimeBetween($startDate = '+15 days', $endDate = '+2 months'), 'subscription_status_id' => 1], 
            ['subscription_end_date' => $this->faker->dateTimeBetween($startDate = '+15 days', $endDate = '+2 months'), 'subscription_status_id' => 2], 
            ['subscription_end_date' => $this->faker->dateTimeBetween($startDate = '+15 days', $endDate = '+2 months'), 'subscription_status_id' => 3]
        ];


        foreach ($olts as $olt) {

            if($olt->olt_active){

                $data = Arr::random($oltData);

                $billing = Billing::create([
                    'olt_id' => $olt->id,
                    'monthly_price' => 20,
                    'subscription_status_id' => $data['subscription_status_id'],
                    'subscription_end_date' => $data['subscription_end_date']
                ]);

                BillingHistory::create([
                    'billing_id' => $billing->id,
                    'transaction_id' => Str::random(10),
                    'user_id' => User::inRandomOrder()->first()->id,
                    'months_paid' => 1
                ]);

            }
            
        }


    }
}
