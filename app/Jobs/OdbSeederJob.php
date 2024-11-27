<?php

namespace App\Jobs;

use App\Models\Odb;
use App\Models\Zone;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Stancl\Tenancy\Facades\Tenancy;

class OdbSeederJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

    /**
     * Create a new job instance.
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Tenancy::find($this->id)->run(function ($tenant) {

            $currentDB = DB::connection()->getDatabaseName();

            \Illuminate\Support\Facades\Log::debug('======== ODB SEEDER ========');
            \Illuminate\Support\Facades\Log::debug('ID ' . $this->id);
            \Illuminate\Support\Facades\Log::debug('TENANT ' . $tenant);
            \Illuminate\Support\Facades\Log::debug('CURRENT DB ' . $currentDB);

            $odbsData = [];

            try {
    
                $url = env('AUX_API_URL');
    
                $data = Http::withHeaders([
                    'AK' => env('API_AUTH_KEY')
                ])->get($url . 'odbs/listing'); 
    
                if(!$data->json()["status"]){

                    $coordinates = [['latitude' => '10.49810811680425', 'longitude' => '-426.90862655639654'], ['latitude' => '10.493550809271058', 'longitude' => '-426.859359741211'], ['latitude' => '10.590421288241636', 'longitude' => '-426.98913574218756'], ['latitude' => '10.455401826918397', 'longitude' => '-426.6293334960938']];

                    for ($i = 1; $i <= 10; $i++) {
            
                        $randomData =  Arr::random($coordinates);
            
                        array_push($odbsData, [
                            'name' => 'ODB ' . $i,
                            'nr_of_ports' => (string) rand(1, 5),
                            'latitude' => $randomData["latitude"],
                            'longitude' => $randomData["longitude"],
                            'zone_id' => Zone::inRandomOrder()->first()->id
                        ]);
                    }  

                } else {

                    // optimizar y comparar tiempos
                
                    $odbsData = $data->json()["data"];

                }
                   
            } catch (\Throwable $th) {
                \Illuminate\Support\Facades\Log::debug('paso algo');
                \Illuminate\Support\Facades\Log::debug($th);
    
                $coordinates = [['latitude' => '10.49810811680425', 'longitude' => '-426.90862655639654'], ['latitude' => '10.493550809271058', 'longitude' => '-426.859359741211'], ['latitude' => '10.590421288241636', 'longitude' => '-426.98913574218756'], ['latitude' => '10.455401826918397', 'longitude' => '-426.6293334960938']];

                for ($i = 1; $i <= 10; $i++) {
        
                    $randomData =  Arr::random($coordinates);
        
                    array_push($odbsData, [
                        'name' => 'ODB ' . $i,
                        'nr_of_ports' => (string) rand(1, 5),
                        'latitude' => $randomData["latitude"],
                        'longitude' => $randomData["longitude"],
                        'zone_id' => (string) Zone::inRandomOrder()->first()->id
                    ]);
                }            
            }
    
            foreach ($odbsData as $data) {

                Odb::create([
                    'name' => $data["name"],
                    'nr_of_ports' => $data["nr_of_ports"],
                    'latitude' => $data["latitude"],
                    'longitude' => $data["longitude"],
                    'zone_id' => Zone::where('smart_olt_id', $data["zone_id"])->first()->id ?? intval($data["zone_id"])
                ]);            
            }
           
        });
    }
}
