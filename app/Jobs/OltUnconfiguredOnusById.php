<?php

namespace App\Jobs;

use App\Models\Olt;
use App\Models\Onu;
use App\Models\OnuType;
use App\Models\Zone;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Stancl\Tenancy\Facades\Tenancy;

class OltUnconfiguredOnusById implements ShouldQueue
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

            \Illuminate\Support\Facades\Log::debug('======== OLT UNC ONUS JOB ========');
            \Illuminate\Support\Facades\Log::debug('ID ' . $this->id);
            \Illuminate\Support\Facades\Log::debug('TENANT ' . $tenant);
            \Illuminate\Support\Facades\Log::debug('CURRENT DB ' . $currentDB);
       
            $olt = Olt::select('id', 'smart_olt_id')->where('smart_olt_id', '!=', null)->where('id', $this->oltId)->first();
            $onusData = [];

            if($olt->smart_olt_id != null){

                $url = env('AUX_API_URL');

                try {
                    $response = Http::retry(3, 500)->timeout(60)->withHeaders([
                        'AK' => env('API_AUTH_KEY')
                    ])->get($url . 'onus/unconfigured_onus_for_olt/' . $olt->smart_olt_id); 
        
                    if($response->json()["status"] && count($response->json()["data"]) > 0){
    
                        $data = $response->json()["data"];
    
                        foreach ($data as $onu) {
                            array_push($onusData, $onu);
                        }
    
                    } 
                } catch (\Throwable $th) {
                    $olt->olt_active = 0;
                    \Illuminate\Support\Facades\Log::debug('paso algo');
                    \Illuminate\Support\Facades\Log::debug($th);
                }

            }
            
            if(count($onusData) > 0){

                foreach ($onusData as $data) {

                    Onu::updateOrCreate([
                        'olt_id' => $olt->id, 
                        'unique_external_id' => $data["external_id"] ?? "no tiene", 
                        'board' => $data["board"],
                        'port' => $data["port"],
                    ],
                    [
                        'serial' => $data["sn"] ?? "no tiene", 
                        'onu_type_id' => OnuType::where('smart_olt_id', $data["onu_type_id"])->first()->id ?? OnuType::inRandomOrder()->first()->id, 
                        'zone_id' => Zone::inRandomOrder()->first()->id, // hacer zone nullable ya que respuesta de smartolt no trae zona
                        'name' => $data["onu"],
                        'latitude' => '10.487271745341458',
                        'longitude' => '-66.93616104021204',
                        'administrative_status_id' => $data["is_disabled"] == 1 ? 0 : 1
                    ]);          
                    
                }

                $olt->olt_active = 1;

            } else {

                Onu::factory(300)->create();

                $olt->olt_active = 1;

            }

            $olt->save();

        });
    }
}
