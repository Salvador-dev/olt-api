<?php

namespace App\Console\Commands;

use App\Models\Olt;
use Exception;
use App\Models\OltTemperature;
use Ndum\Laravel\Snmp;
use Illuminate\Console\Command;

class OltTemp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'olt:temperature';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Guarda diariamente la temperatura de las OLT registradas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $olts = Olt::all();

        foreach ($olts as $olt)
        {
            $host = $olt->ip;
            $community = $olt->snmp_read_only;
            $oltId= $olt->id;
    
            $snmp = new Snmp();
    
            $snmp->newClient($host, 2, $community);
    
            $uptimeOid = '1.3.6.1.2.1.1.3';
            $oltTemperatureOid = '1.3.6.1.2.1.1.6';
    
            $uptimeData = $this->getSnmpData($uptimeOid, $oltId)['values'];
            $temperatureData = $this->getSnmpData($oltTemperatureOid, $oltId)['values'];
            

            // Imprimir en la consola
            $this->info("OLT ID: {$olt->id}, Uptime: $uptimeData[0], Temperature: $temperatureData[0]");

            // Insertar los datos en la tabla olt_temperature
            // OltTemperature::create([
            //     'olt_id' => $olt->id,
            //     'uptime' => $uptime,
            //     'env_temp' => $temperature,
            // ]);
        }
    }

    private function getSnmpData($oids, $id)
    {
        $snmp = $this->getSnmpClient($id);
        $arrayOltCard = [];
        $arrayOltCardValue = [];
        // OID base para el walk
        $baseOid = $oids;
    
        // Realizar el SNMP walk
        $walk = $snmp->walk($baseOid);
    
        // Contador para rastrear el número de OIDs recuperadas
        $oidCount = 0;
    
        // Iterar a través de las OIDs obtenidas durante el walk
        while ($walk->hasOids()) {
            try {
                $oid = $walk->next();
    
                // Incrementar el contador
                $oidCount++;
    
                $arrayOltCard[] = $oid->getOid();
                $arrayOltCardValue[] = $oid->getValue()->getValue();
            } catch (Exception $e) {
                // Manejar errores al recuperar OIDs
                $arrayOltCard[] = ['value' => 'Error al recuperar OID. ' . $e->getMessage()];
            }
        }
    
        // Devolver todos los valores sin aplicar el filtro
        return [
            'values' => $arrayOltCardValue,
            'oids' => $arrayOltCard,
        ];
    }

    private function getSnmpClient($id)
    {

         $olt = Olt::where("id",$id)->first();

         $host = $olt->ip;
         $community = $olt->snmp_read_only;

         $snmp = new Snmp();
         
        
         return  $snmp->newClient($host, 2, $community);

    }
}
