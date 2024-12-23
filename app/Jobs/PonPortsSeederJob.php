<?php

namespace App\Jobs;

use App\Models\AdministrativeStatus;
use App\Models\Olt;
use App\Models\PonPort;
use App\Models\PonType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Stancl\Tenancy\Facades\Tenancy;

class PonPortsSeederJob implements ShouldQueue
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

            \Illuminate\Support\Facades\Log::debug('======== PON PORTS SEEDER ========');
            \Illuminate\Support\Facades\Log::debug('ID ' . $this->id);
            \Illuminate\Support\Facades\Log::debug('TENANT ' . $tenant);
            \Illuminate\Support\Facades\Log::debug('CURRENT DB ' . $currentDB);
       
            try {

                $olts = Olt::select('id', 'smart_olt_id')->get();

                foreach ($olts as $olt) {
                    $url = env('AUX_API_URL');
    
                    $response = Http::withHeaders([
                        'AK' => env('API_AUTH_KEY')
                    ])->get($url . 'olts/pon_ports_details/' . $olt->smart_olt_id); 
        
                    if($response->json()["status"]){

                        $data = $response->json()["data"];

                        foreach ($data as $ponPort) {
                            PonPort::create([
                                'board' => $ponPort['board'],
                                // 'port' => $ponPort['pon_port'],
                                'pon_type_id' => PonType::where("name", $ponPort['pon_type'])->first()->id,
                                'pon_port' => $ponPort['pon_port'],
                                'administrative_status_id' => AdministrativeStatus::where('description', $ponPort['admin_status'])->first()->id,
                                'onus' => strval($ponPort['onus_count']),
                                'onus_active' => intval($ponPort['online_onus_count']),
                                'average_signal' => strval($ponPort['average_signal']),
                                'description' => $ponPort['description'],
                                'tx_power' => $ponPort['tx_power'],
                                'min_range' => $ponPort['min_range'],
                                'max_range' => $ponPort['max_range'],
                                'operational_status' => $ponPort['operational_status'],
                                'olt_id' => $olt->id,
                            ]);   
                        }

                    } 
                }
    
                
                   
            } catch (\Throwable $th) {
                \Illuminate\Support\Facades\Log::debug('paso algo');
                \Illuminate\Support\Facades\Log::debug($th);
              
            }
    
        });
    }
}
