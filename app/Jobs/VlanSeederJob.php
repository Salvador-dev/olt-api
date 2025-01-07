<?php

namespace App\Jobs;

use App\Models\Olt;
use App\Models\Vlan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Stancl\Tenancy\Facades\Tenancy;

class VlanSeederJob implements ShouldQueue
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

            \Illuminate\Support\Facades\Log::debug('======== VLAN SEEDER ========');
            \Illuminate\Support\Facades\Log::debug('ID ' . $this->id);
            \Illuminate\Support\Facades\Log::debug('TENANT ' . $tenant);
            \Illuminate\Support\Facades\Log::debug('CURRENT DB ' . $currentDB);
       

            $olts = Olt::select('id', 'smart_olt_id')->get();

            foreach ($olts as $olt) {
                $url = env('AUX_API_URL');

                try {
                    
                    $response = Http::retry(3, 500)->timeout(60)->withHeaders([
                        'AK' => env('API_AUTH_KEY')
                    ])->get($url . 'olts/vlans_by_olt/' . $olt->smart_olt_id); 
        
                    if($response->json()["status"]){
    
                        $data = $response->json()["data"];
    
                        foreach ($data as $vlan) {
                            Vlan::create([
                                "vlan_id" => $vlan['id'],
                                "description" => $vlan['description'],
                                "olt_id" => $olt->id,
                                "scope" => $vlan['scope']
                            ]);   
                        }
    
                    } 
                    
                } catch (\Throwable $th) {
                    \Illuminate\Support\Facades\Log::debug('paso algo');
                    \Illuminate\Support\Facades\Log::debug($th);                
                }

            }
    
        });
    }
}
