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

class UplinkSeederJob implements ShouldQueue
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

            \Illuminate\Support\Facades\Log::debug('======== UPLINKS SEEDER ========');
            \Illuminate\Support\Facades\Log::debug('ID ' . $this->id);
            \Illuminate\Support\Facades\Log::debug('TENANT ' . $tenant);
            \Illuminate\Support\Facades\Log::debug('CURRENT DB ' . $currentDB);
       

            $olts = Olt::select('id', 'smart_olt_id')->get();

            foreach ($olts as $olt) {
                $url = env('AUX_API_URL');

                try {
                    
                    $response = Http::withHeaders([
                        'AK' => env('API_AUTH_KEY')
                    ])->get($url . 'olts/uplinks_by_olt/' . $olt->smart_olt_id); 
        
                    if($response->json()["status"]){
    
                        $data = $response->json()["data"];
    
                        foreach ($data as $uplink) {
                            Uplink::create([
                                'vlan_tag' => $uplink['vlan_tag'],
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
                                'olt_id' => $olt->id,
                                "name" => $uplink['name'],

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
