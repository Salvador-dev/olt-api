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

class OltPonPortsById implements ShouldQueue
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

            \Illuminate\Support\Facades\Log::debug('======== OLT PON PORTS JOB ========');
            \Illuminate\Support\Facades\Log::debug('ID ' . $this->id);
            \Illuminate\Support\Facades\Log::debug('TENANT ' . $tenant);
            \Illuminate\Support\Facades\Log::debug('CURRENT DB ' . $currentDB);
       
            $olt = Olt::select('id', 'smart_olt_id')->where('smart_olt_id', '!=', null)->where('id', $this->oltId)->first();

            if($olt->smart_olt_id != null){

                $url = env('AUX_API_URL');

                try {
                    $response = Http::retry(3, 500)->timeout(60)->withHeaders([
                        'AK' => env('API_AUTH_KEY')
                    ])->get($url . 'olts/pon_ports_details/' . $olt->smart_olt_id); 
        
                    if($response->json()["status"]){
    
                        $data = $response->json()["data"];
    
                        foreach ($data as $ponPort) {
                            PonPort::updateOrCreate([
                                'pon_port' => $ponPort['pon_port'],
                                'olt_id' => $olt->id,
                            ],
                            [
                                'board' => intval($ponPort['board']),
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
