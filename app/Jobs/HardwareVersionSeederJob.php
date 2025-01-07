<?php

namespace App\Jobs;

use App\Models\HardwareVersion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Stancl\Tenancy\Facades\Tenancy;

class HardwareVersionSeederJob implements ShouldQueue
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

            \Illuminate\Support\Facades\Log::debug('======== HARDWARE VERSION SEEDER ========');
            \Illuminate\Support\Facades\Log::debug('ID ' . $this->id);
            \Illuminate\Support\Facades\Log::debug('TENANT ' . $tenant);
            \Illuminate\Support\Facades\Log::debug('CURRENT DB ' . $currentDB);

            $hardwareVersionsData = [];

            try {
    
                $url = env('AUX_API_URL');
    
                $data = Http::retry(3, 500)->timeout(60)->withHeaders([
                    'AK' => env('API_AUTH_KEY')
                ])->get($url . 'olts/listing');
                
                if(!$data->json()["status"]){

                    $hardwareVersionsData = ['Huawei-MA5800-X7', 'Huawei-MA5800-X15', 'Huawei-MA5680T', 'Huawei-MA5603','Huawei-MA5600', 'ZTE-C300', 'Huawei-MA5800-X17', 'ZTE-C320', 'Huawei-MA5600T'];

                } else {

                    // optimizar y comparar tiempos

                    $data = $data->json()["data"];
                
                    foreach ($data as $data) {
                        if(!in_array($data["olt_hardware_version"], $hardwareVersionsData)){
                            array_push($hardwareVersionsData, $data["olt_hardware_version"]);
                        }
                    }

                }
                   
            } catch (\Throwable $th) {
    
                \Illuminate\Support\Facades\Log::debug('paso algo');
                \Illuminate\Support\Facades\Log::debug($th);
    
                $hardwareVersionsData = ['Huawei-MA5800-X7', 'Huawei-MA5800-X15', 'Huawei-MA5680T', 'Huawei-MA5603','Huawei-MA5600', 'ZTE-C300', 'Huawei-MA5800-X17', 'ZTE-C320', 'Huawei-MA5600T'];
            }
    
            foreach ($hardwareVersionsData as $data) {
                HardwareVersion::create(['name' => $data]);
            }
           
        });

    }
}
