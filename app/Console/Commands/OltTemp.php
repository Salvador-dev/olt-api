<?php

namespace App\Console\Commands;

use App\Models\Olt;
use Exception;
use App\Models\OltTemperature;
use Ndum\Laravel\Snmp;
use DateTime;
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
            $oltTemperatureOid = '1.3.6.1.4.1.2011.2.6.7.1.1.2.1.10';
    
            $uptimeData = $this->getSnmpData($uptimeOid, $oltId)['values'];
            $temperatureData = $this->getSnmpData($oltTemperatureOid, $oltId)['values'];


            $uptimeFormatted = $this->convertTimeTicksToTime($uptimeData[0]);

            $temperature = $this->sumTemperatureData($temperatureData);
            // Imprimir en la consola
            $this->info("OLT ID: {$olt->id}, Uptime:  $uptimeFormatted, Temperature: $temperature");

            // Insertar los datos en la tabla olt_temperature
             OltTemperature::create([
                 'olt_id' => $olt->id,
                 'uptime' => $uptimeFormatted,
                 'env_temp' => $temperature,
             ]);
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
    private function convertTimeTicksToTime($timeTicks)
    {
  
        $seconds = $timeTicks / 100;
    
        $dateTime = new DateTime('@' . $seconds);
    
        $days = floor($seconds / (24 * 60 * 60));

        $formattedTime = $dateTime->format('H:i:s');
    
        $resultText = "$days días, $formattedTime";
    
        return $resultText;
    }

    private function sumTemperatureData($temperatureData) {
        $sum = 0;
        $count = 0;
    
        foreach ($temperatureData as $value) {
            // Ignorar el valor 2147483647
            if ($value !== 2147483647) {
                // Sumar los valores distintos a 2147483647
                $sum += $value;
                $count++;
            }
        }
    
        // Dividir la suma por la cantidad total de valores distintos de 2147483647
        $average = ($count > 0) ? ($sum / $count) : 0;
        
        return floor($average) ;
    }
    
}
