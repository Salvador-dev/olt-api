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

        foreach ($olts as $olt) {

            if($olt->olt_active){

                $billing = Billing::create([
                    'olt_id' => $olt->id,
                    'monthly_price' => 20,
                    'subscription_status_id' => rand(0,3),
                    'subscription_end_date' => $this->faker->dateTimeBetween($startDate = '+1 month', $endDate = '+16 month')
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
