<?php

namespace App\Jobs;

use App\Http\Controllers\SnmpController;
use App\Models\Billing;
use App\Models\Olt;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SNMPJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $controller = new SnmpController();
            $controller->uplinkRegister($this->id);
            $controller->ponPortsData($this->id);
            $controller->oltCardRegister($this->id);
            $controller->vlanRegister($this->id);

            // Si todas las operaciones fueron exitosas, actualiza la columna olt_active a true
            $olt = Olt::where('id', $this->id);

            $olt->olt_active = 1;

            $olt->save();

            $billing = Billing::where('olt_id', $olt->id)->first();
  
            if (is_null($billing)) {
                Billing::create([
                    'olt_id' => $olt->id,
                    'monthly_price' => 20,
                    'subscription_status_id' => 0,
                    'subscription_end_date' => null
                ]);            
            } else {
                Billing::where('olt_id', $olt->id)->update([
                    'olt_id' => $olt->id,
                    'monthly_price' => 20,
                    'subscription_status_id' => 0,
                    'subscription_end_date' => null
                ]);              
            }

        } catch (Exception $e) {
            Olt::where('id', $this->id)->update(['olt_active' => 0]);
            return "Error en la activación: " . $e->getMessage();
        }
    }
}
