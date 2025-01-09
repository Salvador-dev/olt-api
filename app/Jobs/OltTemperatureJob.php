<?php

namespace App\Jobs;

use App\Models\Olt;
use App\Models\OltTemperature;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Stancl\Tenancy\Facades\Tenancy;

class OltTemperatureJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id, $olt;

    /**
     * Create a new job instance.
     */
    public function __construct($id, $olt)
    {
        $this->id = $id;
        $this->olt = $olt;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Tenancy::find($this->id)->run(function ($tenant) {

            $currentDB = DB::connection()->getDatabaseName();

            \Illuminate\Support\Facades\Log::debug('======== OLT TEMPERATURE JOB ========');
            \Illuminate\Support\Facades\Log::debug('ID ' . $this->id);
            // \Illuminate\Support\Facades\Log::debug('TENANT ' . $tenant);
            \Illuminate\Support\Facades\Log::debug('CURRENT DB ' . $currentDB);

            $oltData = [];

            try {
    
                $url = env('AUX_API_URL');
    
                $data = Http::retry(3, 500)->timeout(60)->withHeaders([
                    'AK' => env('API_AUTH_KEY')
                ])->get($url . 'olts/temperature_and_uptime');     

                if($data->json()["status"]){

                    // optimizar y comparar tiempos
                
                    $oltData = $data->json()["data"];

                    \Illuminate\Support\Facades\Log::debug($oltData);


                }
                   
            } catch (\Throwable $th) {
                \Illuminate\Support\Facades\Log::debug('paso algo');
                \Illuminate\Support\Facades\Log::debug($th);
            }

            if(count($oltData)){

                foreach ($oltData as $data) {

                    $olt = Olt::where('smart_olt_id', $data['olt_id'])->first();

                    if($olt != null && $olt->name == $data["olt_name"]){

                        $oltTemperature = OltTemperature::where('olt_id', $olt->id)->first();

                        if($oltTemperature == null || !$oltTemperature->created_at->isToday()){

                            OltTemperature::create([
                                'olt_id' => $olt->id,
                                'uptime' => $data["uptime"] == 'N/A' ? null : $data["uptime"],
                                'env_temp' => strlen($data["env_temp"]) ? floatval(explode('°C', $data["env_temp"])[0]) : null
                            ]);

                        } 
                    }
                }
            }
    
        // });
    }
}
