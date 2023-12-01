<?php

namespace App\Http\Controllers; 
use FreeDSx\Snmp\SnmpClient;
use Exception;
use App\Models\PonPort;
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

    public function ponPortsData()
    {
        // Obtener los resultados de cada método
        $result1 = $this->OnusByPort();
        $result2 = $this->portName();
        $result3 = $this->powerTxOLT();
        $result4 = $this->portType();
        $result5 = $this->portStatus();
        $result6 = $this->ActiveOnus();
    
        // Asegurar que todos los resultados tengan la misma longitud
        $maxCount = max(
            count($result1),
            count($result2),
            count($result3),
            count($result4),
            count($result5),
            count($result6)
        );
    
        // Combinar los resultados en un solo objeto sin anidaciones
        $combinedResults = [];
    
        for ($i = 0; $i < $maxCount; $i++) {
            // Buscar el patrón "SLOT/PORT" en la cadena
            $portNameParts = explode('/', isset($result2[$i]) ? $result2[$i] : '');

            $combinedResult = (object)[
                'slot' => isset($portNameParts[1]) ? $portNameParts[1] : null,
                'port' => isset($portNameParts[2]) ? $portNameParts[2] : null,
                'cantidad_onus' => isset($result1[$i]) ? ($result1[$i]->value ?? $result1[$i]) : null,
                'portName' => isset($result2[$i]) ? ($result2[$i]->value ?? $result2[$i]) : null,
                'powerTxOLT' => isset($result3[$i]) ? ($result3[$i]->value ?? $result3[$i]) : null,
                'portType' => isset($result4[$i]) ? ($result4[$i]->value ?? $result4[$i]) : null,
                'portStatus' => isset($result5[$i]) ? ($result5[$i]->status ?? $result5[$i]) : null,
                'cantidad_online_onus' => isset($result6[$i]) ? ($result6[$i]->activeCount ?? $result6[$i]) : 0,
                'description' => '',
                'rango_maximo' => '20km',
                'rango_minimo' => '0',
            ];
    
            $combinedResults[] = $combinedResult;
        }

        foreach ($combinedResults as $combinedResult) {
            $PonPort = new PonPort();
        
            switch ($combinedResult->portType) {
                case 'GPON':
                    $PonPort->pon_type_id = 1;
                    break;
                case 'EPON':
                    $PonPort->pon_type_id = 2;
                    break;
                case 'GPON | EPON':
                    $PonPort->pon_type_id = 3;
                    break;
                default:
                    $PonPort->pon_type_id = 1;
                    break;
            }
            $PonPort->admin_status = $combinedResult->portStatus;
            $PonPort->onus = $combinedResult->cantidad_onus;
            if ($combinedResult->powerTxOLT >= -8.5 && $combinedResult->powerTxOLT <= 1.0) {
                $PonPort->average_signal = 'Critical';
            } elseif ($combinedResult->powerTxOLT >= -9.5 && $combinedResult->powerTxOLT <= 3.0) {
                $PonPort->average_signal = 'Warning';
            } else {
                $PonPort->average_signal = 'Very Good';
                
            }
            $PonPort->description = $combinedResult->description;
            $PonPort->tx_power = $combinedResult->powerTxOLT;
            $PonPort->board = $combinedResult->slot;
            $PonPort->range = $combinedResult->rango_minimo . ' - ' . $combinedResult->rango_maximo;
            $PonPort->min_range = $combinedResult->rango_minimo;
            $PonPort->max_range = $combinedResult->rango_maximo;
            $PonPort->operational_status = $combinedResult->portStatus;
            $PonPort->olt_id = 1;
            $PonPort->onus_active = $combinedResult->cantidad_online_onus;

            // Guardar el registro en la base de datos
            $PonPort->save();
        }
    
        return $combinedResults;
    }
    
    
    public function ActiveOnus()
    {
        // Obtener la información de los Onus por puerto
        $onusData = $this->OnusByPort();

        // Obtener la información de los puertos activos
        $activePorts = $this->ActiveOnusByPort();

        // Crear un array asociativo para almacenar el resultado combinado
        $combinedResult = [];

        // Iterar sobre la información de los Onus por puerto
        foreach ($onusData as $onus) {
            $portNumber = $onus->port;

            // Inicializar el objeto para el puerto actual si no existe
            if (!isset($combinedResult[$portNumber])) {
                $combinedResult[$portNumber] = (object)[
                    'activeCount' => 0,
                    'totalActiveOnus' => $onus->value,
                ];
            }
        }

        // Iterar sobre la información de los puertos activos
        foreach ($activePorts as $activePort) {
            $portNumber = $activePort->port;

            // Inicializar el objeto para el puerto actual si no existe
            if (!isset($combinedResult[$portNumber])) {
                $combinedResult[$portNumber] = (object)[
                    'activeCount' => 0,
                    'totalActiveOnus' => 0,
                ];
            }

            // Actualizar el objeto con la información de los puertos activos
            $combinedResult[$portNumber]->activeCount = $activePort->activeCount;
        }

        // Alineación: Si totalActiveOnus es cero pero activeCount no lo es, desplazar hacia la derecha
        $previousTotal = 0;
        foreach ($combinedResult as &$result) {
            if (!property_exists($result, 'totalActiveOnus')) {
                $result->totalActiveOnus = $previousTotal;
            } else {
                $previousTotal = $result->totalActiveOnus;
            }
        }

        // Retornar los resultados como un array de objetos
        return array_values($combinedResult);
    }

        public function ActiveOnusByPort()
    {
        $snmp = $this->getSnmpClient();
        
        // OID base para el walk
        $baseOid = '1.3.6.1.4.1.2011.6.128.1.1.2.46.1.15';
        
        // Realizar el SNMP walk
        $walk = $snmp->walk($baseOid);
        
        $portGroups = []; // Array para almacenar los objetos por grupo de puertos
        
        // Iterar a través de las OIDs obtenidas durante el walk
        while ($walk->hasOids()) {
            try {
                $oid = $walk->next();
        
                // Obtener el número de puerto desde la OID
                preg_match('/\.(\d+)\.\d+$/', $oid->getOid(), $matches);
                $currentPort = isset($matches[1]) ? $matches[1] : null;
        
                // Obtener el valor de la OID
                $value = $oid->getValue()->getValue();
        
                // Inicializar el objeto para el grupo de puertos actual si no existe
                if (!isset($portGroups[$currentPort])) {
                    $portGroups[$currentPort] = (object)[
                        'activeCount' => 0,
                        'port' => $currentPort,
                    ];
                }
        
                // Incrementar el conteo si el valor es igual a 1
                if ($value === 1) {
                    $portGroups[$currentPort]->activeCount++;
                }
            } catch (Exception $e) {
                // Manejar errores si es necesario
                return "Error al recuperar OID. " . $e->getMessage();
            }
        }
        
        // Retornar los resultados como un array de objetos
        return array_values($portGroups);
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
                
                // Obtener el número de puerto desde la OID
                preg_match('/\.(\d+)\.(\d+)$/', $oid->getOid(), $matches);
                $currentPort = isset($matches[2]) ? $matches[2] : null;

                $value = $oid->getValue()->getValue();

                // Crear un objeto Onus y agregarlo al array
                $onus = new stdClass();
                $onus->value = $value;
                $onus->port = $currentPort;
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
        $portNameArray = [];
    
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
                    // Agregar el resultado al arreglo asociativo
                    $portNameArray[] = $oid->getValue()->getValue();
                }
            } catch (Exception $e) {
                // Manejar errores al recuperar OIDs
                $portNameArray[] = ['value' => 'Error al recuperar OID. ' . $e->getMessage()];
            }
        }
    
        return $portNameArray;
    }
    
    public function powerTxOLT()
    {
        $snmp = $this->getSnmpClient();
        $powerTxArray = [];
    
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
                $result = ($value !== 2147483647) ? number_format($value * 0.01, 2) : 'Valor Desconocido';
    
                // Agregar el resultado al arreglo asociativo
                $powerTxArray[] = $result;
            } catch (Exception $e) {
                // Manejar errores al recuperar OIDs
                $powerTxArray[] = ['value' => 'Error al recuperar OID. ' . $e->getMessage()];
            }
        }
    
        return $powerTxArray;
    }
    public function portType()
    {
        $snmp = $this->getSnmpClient();
        $portTypeArray = [];
    
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
                $result = ($value === 250) ? 'GPON' : $value;
    
                // Agregar el resultado al arreglo asociativo
                $portTypeArray[] =  $result;
            } catch (Exception $e) {
                // Manejar errores al recuperar OIDs
                $portTypeArray[] = ['value' => 'Error al recuperar OID. ' . $e->getMessage()];
            }
        }
    
        return $portTypeArray;
    }
    
    public function portStatus()
    {
        $snmp = $this->getSnmpClient();
        $portStatusArray = [];
    
        // OID base para el walk
        $baseOid = '1.3.6.1.2.1.2.2.1.8';
    
        // Realizar el SNMP walk
        $walk = $snmp->walk($baseOid);
    
        // Iterar a través de las OIDs obtenidas durante el walk
        while ($walk->hasOids()) {
            try {
                $oid = $walk->next();
                $value = $oid->getValue()->getValue();
    
    
                // Agregar el resultado al arreglo asociativo
                $portStatusArray[] = $value;
            } catch (Exception $e) {
                // Manejar errores al recuperar OIDs
                $portStatusArray[] = ['status' => 'Error al recuperar OID. ' . $e->getMessage()];
            }
        }
    
        return $portStatusArray;
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
    
        foreach ($specificOidsAndDescriptions as $specificOid => $description) {
            try {
                $this->saveOidToDatabase($specificOid, $description);
            } catch (Exception $e) {
                echo "Error al recuperar OID. ".$e->getMessage().PHP_EOL;
            }
        }
    }
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
