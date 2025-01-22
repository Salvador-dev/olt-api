<?php

namespace App\Jobs;

use App\Models\Olt;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Stancl\Tenancy\Facades\Tenancy;

class GetSmartOltIdJob implements ShouldQueue
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

            \Illuminate\Support\Facades\Log::debug('======== OLT ID SEEDER ========');
            \Illuminate\Support\Facades\Log::debug('ID ' . $this->id);
            \Illuminate\Support\Facades\Log::debug('TENANT ' . $tenant);
            \Illuminate\Support\Facades\Log::debug('CURRENT DB ' . $currentDB);

            $oltData = [];

            try {
    
                $url = env('AUX_API_URL');
    
                $data = Http::retry(3, 500)->timeout(60)->withHeaders([
                    'AK' => env('API_AUTH_KEY')
                ])->get($url . 'olts/listing');     

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

                $olts = Olt::all();

                foreach ($olts as $olt) {

                    if($olt->smart_olt_id == null){

                        $dataOlt = null;

                        foreach ($oltData as $item) {
                            if($item['ip'] == $olt->ip){
                                $dataOlt = $item;
                            }
                        }
    
                        if($dataOlt != null){
    
                            $olt->smart_olt_id = $dataOlt['id'];
                            $olt->save();
    
                        }
                    }
                }
            }
    
        });
    }
}
