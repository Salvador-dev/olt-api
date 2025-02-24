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

class OltVlansById implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id, $oltId;

    /**
     * Create a new job instance.
     */
    public function __construct($id, $oltId)
    {
        $this->id = $id;
        $this->oltId = $oltId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Tenancy::find($this->id)->run(function ($tenant) {

            $currentDB = DB::connection()->getDatabaseName();

            \Illuminate\Support\Facades\Log::debug('======== OLT VLANS JOB ========');
            \Illuminate\Support\Facades\Log::debug('ID ' . $this->id);
            \Illuminate\Support\Facades\Log::debug('TENANT ' . $tenant);
            \Illuminate\Support\Facades\Log::debug('CURRENT DB ' . $currentDB);
       
            $olt = Olt::select('id', 'smart_olt_id')->where('smart_olt_id', '!=', null)->where('id', $this->oltId)->first();

            if($olt->smart_olt_id != null){

                $url = env('AUX_API_URL');

                try {
                    $response = Http::retry(3, 500)->timeout(60)->withHeaders([
                        'AK' => env('API_AUTH_KEY')
                    ])->get($url . 'olts/vlans_by_olt/' . $olt->smart_olt_id); 
        
                    if($response->json()["status"]){
    
                        $data = $response->json()["data"];
    
                        foreach ($data as $vlan) {
                            Vlan::updateOrCreate([
                                "olt_id" => $olt->id,
                                "vlan_id" => $vlan['id'],
                            ],
                            [
                                "description" => $vlan['description'],
                                "scope" => $vlan['scope']
                            ]);   
                        }
    
                    } 
                } catch (\Throwable $th) {
                    $olt->olt_active = 0;
                    \Illuminate\Support\Facades\Log::debug('paso algo');
                    \Illuminate\Support\Facades\Log::debug($th);
                }

            }

            $olt->save();

        });
    }
}
