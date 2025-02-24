<?php

namespace App\Jobs;

use App\Models\AdministrativeStatus;
use App\Models\Olt;
use App\Models\Uplink;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Stancl\Tenancy\Facades\Tenancy;

class OltUplinksById implements ShouldQueue
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

            \Illuminate\Support\Facades\Log::debug('======== OLT UPLINKS JOB ========');
            \Illuminate\Support\Facades\Log::debug('ID ' . $this->id);
            \Illuminate\Support\Facades\Log::debug('TENANT ' . $tenant);
            \Illuminate\Support\Facades\Log::debug('CURRENT DB ' . $currentDB);
       
            $olt = Olt::select('id', 'smart_olt_id')->where('smart_olt_id', '!=', null)->where('id', $this->oltId)->first();

            if($olt->smart_olt_id != null){

                $url = env('AUX_API_URL');

                try {
                    $response = Http::retry(3, 500)->timeout(60)->withHeaders([
                        'AK' => env('API_AUTH_KEY')
                    ])->get($url . 'olts/uplinks_by_olt/' . $olt->smart_olt_id); 
        
                    if($response->json()["status"]){
    
                        $data = $response->json()["data"];
    
                        foreach ($data as $uplink) {
                            Uplink::updateOrCreate([
                                'olt_id' => $olt->id,
                                "name" => $uplink['name'],
                                'vlan_tag' => $uplink['vlan_tag'],
                            ],
                            [
                                'status' => $uplink['status'],
                                'mode' => $uplink['mode'],
                                'administrative_status_id' => AdministrativeStatus::where('description', $uplink['admin_status'])->first()->id,
                                'negotiation' => strval($uplink['negotiation_auto']),
                                'mtu' => intval($uplink['mtu']),
                                'type' => strval($uplink['type']),
                                'description' => $uplink['description'],
                                'wavelength' => $uplink['wavelength'],
                                'temperature' => $uplink['temperature'],
                                'pvid' => $uplink['pvid'],
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
