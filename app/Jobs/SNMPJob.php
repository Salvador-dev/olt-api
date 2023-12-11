<?php

namespace App\Jobs;

use App\Http\Controllers\SnmpController;
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
            Olt::where('id', $this->id)->update(['olt_active' => 1]);
        } catch (Exception $e) {
            Olt::where('id', $this->id)->update(['olt_active' => 0]);
            return "Error en la activación: " . $e->getMessage();
        }
    }
}
