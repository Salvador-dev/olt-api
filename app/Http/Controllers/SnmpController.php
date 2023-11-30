<?php

namespace App\Http\Controllers; 
use FreeDSx\Snmp\SnmpClient;
use Exception;
use App\Models\HardwareVersion;
use App\Models\Oid;
use stdClass;

class SnmpController extends Controller
{

    private function getSnmpClient()
    {
        return new SnmpClient([
            'host' => '172.29.0.2',
            'version' => 2,
            'community' => 'public',
        ]);
    }

    public function processPortData()
    {
        $data = [];
    
        $ports = $this->OnusByPort(); // Obtén la lista de puertos
    
            $portData = [
                'port' => $this->portName(),
                'type' => $this->PortType(),
                'status' => $this->PortStatus(),
                'admin_state' => $this->PortStatus(),
                'tx_power' => $this->powerTxOLT(),
                'description' => '',
                'cantidad_onus' => $this->OnusByPort(),
                'cantidad_online_onus' => $this->ActiveOnusByPort(),
                'rango_maximo' => '20km',
                'rango_minimo' => '0',
            ];
    
            $data[] = $portData;
    
        return ['data' => $data];
    }
    
    public function OnusByPort()
    {
        $snmp = $this->getSnmpClient();
        
        // OID base para el walk
        $baseOid = '1.3.6.1.4.1.2011.6.128.1.1.2.21.1.16';
        
        // Realizar el SNMP walk
        $walk = $snmp->walk($baseOid);
    
        $onusArray = []; // Array para almacenar los objetos Onus
    
        // Iterar a través de las OIDs obtenidas durante el walk
        while ($walk->hasOids()) {
            try {
                $oid = $walk->next();
                $value = $oid->getValue()->getValue();
    
                // Crear un objeto Onus y agregarlo al array
                $onus = new stdClass();
                $onus->value = $value;
                $onusArray[] = $onus;
            } catch (Exception $e) {
                echo "Error al recuperar OID. " . $e->getMessage() . PHP_EOL;
            }
        }
    
        return $onusArray;
    }
    

    public function portName()
    {
         $snmp = $this->getSnmpClient();
    
        // OID base para el walk
        $baseOid = '1.3.6.1.2.1.2.2.1.2';
    
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
    
                // Ignorar los primeros 7 resultados
                if ($oidCount > 15) {
                    echo sprintf($oid->getValue()->getValue()) . PHP_EOL;
                }
            } catch (Exception $e) {
                echo "Error al recuperar OID. " . $e->getMessage() . PHP_EOL;
            }
        }
    }
    
public function powerTxOLT()
    {
        $snmp = $this->getSnmpClient();

        // OID base para el walk
        $baseOid = '1.3.6.1.4.1.2011.6.128.1.1.2.23.1.4';

        // Realizar el SNMP walk
        $walk = $snmp->walk($baseOid);

        // Iterar a través de las OIDs obtenidas durante el walk
        while ($walk->hasOids()) {
            try {
                $oid = $walk->next();
                $value = $oid->getValue()->getValue();

                // Multiplicar por 0.01 y verificar si es 2147483647
                $result = ($value !== 2147483647) ? $value * 0.01 : 'Valor Desconocido';

                echo sprintf($result) . PHP_EOL;
            } catch (Exception $e) {
                echo "Error al recuperar OID. " . $e->getMessage() . PHP_EOL;
            }
        }
    }
  
public function portType()
    {
        $snmp = $this->getSnmpClient();

        // OID base para el walk
        $baseOid = '1.3.6.1.2.1.2.2.1.3';

        // Realizar el SNMP walk
        $walk = $snmp->walk($baseOid);

        $count = 0; // Contador para las primeras 15 entradas

        // Iterar a través de las OIDs obtenidas durante el walk
        while ($walk->hasOids()) {
            try {
                $oid = $walk->next();

                // Ignorar las primeras 15 entradas
                if ($count < 15) {
                    $count++;
                    continue;
                }

                $value = $oid->getValue()->getValue();

                // Verificar si el valor es 250
                $result = ($value === 250) ? 'gpon' : $value;

                echo sprintf($result) . PHP_EOL;
            } catch (Exception $e) {
                echo "Error al recuperar OID. " . $e->getMessage() . PHP_EOL;
            }
        }
    }

public function portStatus()
    {
        $snmp = $this->getSnmpClient();

        // OID base para el walk
        $baseOid = '1.3.6.1.4.1.2011.6.128.1.1.2.21.1.13';

        // Realizar el SNMP walk
        $walk = $snmp->walk($baseOid);

        // Iterar a través de las OIDs obtenidas durante el walk
        while ($walk->hasOids()) {
            try {
                $oid = $walk->next();

                $value = $oid->getValue()->getValue();

                // Validar el valor y mostrar el estado correspondiente
                if ($value === 1) {
                    $status = 'UP';
                } elseif ($value === 2) {
                    $status = 'Down';
                } else {
                    $status = 'No disponible';
                }

                echo sprintf($status) . PHP_EOL;
            } catch (Exception $e) {
                echo "Error al recuperar OID. " . $e->getMessage() . PHP_EOL;
            }
        }
    }




    public function saveHuaweiOid()
    {
        // Lista de OIDs específicas y sus descripciones
        $specificOidsAndDescriptions = [
            '1.3.6.1.4.1.2011.6.128.1.1.2.21.1.1' => 'Lista de puertos de la OLT',
            '1.3.6.1.4.1.2011.6.128.1.1.2.21.1.10' => 'Estado del puerto GPON (1 online, 2 offline)',
            '1.3.6.1.4.1.2011.6.128.1.1.2.43.1.3' => 'Serial PON por puerto',
            '1.3.6.1.4.1.2011.6.128.1.1.2.51.1.1' => 'Temperatura de la ONT',
            '1.3.6.1.4.1.2011.6.128.1.1.2.21.1.16' => 'Número de ONUs agregadas al puerto',
            '1.3.6.1.4.1.2011.6.128.1.1.2.21.1.13' => 'Estado del módulo óptico en puerto GPON',
            '1.3.6.1.4.1.2011.6.128.1.1.2.21.1.19' => 'Razón de desconexión en ese puerto',
            '1.3.6.1.4.1.2011.6.128.1.1.2.23.1.1' => 'Temperatura del puerto en grados centígrados',
            '1.3.6.1.4.1.2011.6.128.1.1.2.23.1.4' => 'Power TX en unidades de 0.01 dBm',
        ];
    
        // Iterar a través de las OIDs específicas y sus descripciones
        foreach ($specificOidsAndDescriptions as $specificOid => $description) {
            try {
                $this->saveOidToDatabase($specificOid, $description);
            } catch (Exception $e) {
                echo "Error al recuperar OID. ".$e->getMessage().PHP_EOL;
            }
        }
    }
    
    // Nuevo método para guardar en la base de datos
        protected function saveOidToDatabase($oid, $description)
    {
        // Crear un nuevo registro en la tabla oids
        Oid::create([
            'hardware_version_id' => 2,
            'oid' => $oid,
            'description' => $description,
        ]);

        // Imprimir información de la OID
        echo sprintf("OID: %s, Descripción: %s - Guardado en la base de datos", $oid, $description).PHP_EOL;
    }
}
