<?php

namespace App\Jobs;

use App\Models\Billing;
use App\Models\BillingHistory;
use App\Models\Olt;
use App\Models\User;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Stancl\Tenancy\Facades\Tenancy;

class BillingSeederJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

     /**
     * The current Faker instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    protected $id;

    /**
     * Create a new job instance.
     */
    public function __construct($id)
    {
        $this->id = $id;
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
     * Execute the job.
     */
    public function handle(): void
    {

        Tenancy::find($this->id)->run(function ($tenant) {

            $currentDB = DB::connection()->getDatabaseName();
            
            \Illuminate\Support\Facades\Log::debug('======== BILLING SEEDER ========');
            \Illuminate\Support\Facades\Log::debug('ID ' . $this->id);
            \Illuminate\Support\Facades\Log::debug('TENANT ' . $tenant);
            \Illuminate\Support\Facades\Log::debug('CURRENT DB ' . $currentDB);


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
           
        });

    }
}
