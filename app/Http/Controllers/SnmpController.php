<?php

namespace App\Http\Controllers; 
use App\Jobs\SNMPJob;
use FreeDSx\Snmp\SnmpClient;
use Exception;
use App\Models\PonPort;
use App\Models\Uplink;
use App\Models\Vlan;
use App\Models\Olt;
use App\Models\OltCard;
use App\Models\Oid;
use stdClass;
use Ndum\Laravel\Snmp;

class SnmpController extends Controller
{

     public function getSnmpClient($id)
    {

         $olt = Olt::where("id",$id)->first();

         $host = $olt->ip;
         $community = $olt->snmp_read_only;

         $snmp = new Snmp();
         
        
         return  $snmp->newClient($host, 2, $community);

    }

    public function activeOlt($id)
    {
          Olt::where('id', $id)->update(['olt_active' => 2]);
        // Despacha la Job a la cola
        SNMPJob::dispatch($id);
    
        return "Activación en cola. Esto puede tardar un tiempo.";
    }
    public function ponPortsData($id)
    {
        do {
            try {
                // Obtener los resultados de cada método
                $result1 = $this->portName($id);
                $result2 = $this->powerTxOLT($id);
                $result3 = $this->portType($id);
                $result4 = $this->portStatus($id);
                $result5 = $this->ActiveOnus($id);
                $result6 = $this->OnusByPort($id);
        
                // Asegurar que todos los resultados tengan la misma longitud
                $length = count($result6) - 1;
        
                // Combinar los resultados en un solo objeto sin anidaciones
                $combinedResults = [];  
             
                for ($i = 0; $i < $length; $i++) {
                    // Buscar el patrón "SLOT/PORT" en la cadena
                    $portNameParts = explode('/', isset($result1[$i]) ? $result1[$i] : '');
                    
                    $combinedResult = (object)[
                        'board' => isset($portNameParts[1]) ? $portNameParts[1] : null,
                        'port' => isset($portNameParts[2]) ? $portNameParts[2] : null,
                        'cantidad_onus' => isset($result5[$i]) ? ($result5[$i]->totalActiveOnus ?? $result5[$i]) : null,
                        'portName' => isset($result1[$i]) ? ($result1[$i]->value ?? $result1[$i]) : null,
                        'tx_power' => isset($result2[$i]) ? ($result2[$i] ?? $result2[$i]) : null,
                        'portType' => isset($result3[$i]) ? ($result3[$i]->value ?? $result3[$i]) : null,
                        'portStatus' => isset($result4[$i]) ? ($result4[$i]->status ?? $result4[$i]) : null,
                        'onus_active' => isset($result5[$i]) ? ($result5[$i]->activeCount) : 0,
                        'description' => '',
                        'rango_maximo' => '20km',
                        'rango_minimo' => '0',
                    ];
        
                    $combinedResults[] = $combinedResult;

                }
    
                foreach ($combinedResults as $combinedResult) {
                    $PonPort = new PonPort();
                
                    $PonPort->admin_status = $combinedResult->portStatus;
                    $PonPort->onus_active = $combinedResult->onus_active;
                    $PonPort->onus = $combinedResult->cantidad_onus;
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
                    // Lógica para determinar el valor de average_signal
                    if ($combinedResult->tx_power >= -8.5 && $combinedResult->tx_power <= 1.0) {
                        $PonPort->average_signal = 'Critical';
                    } elseif ($combinedResult->tx_power >= -9.5 && $combinedResult->tx_power <= 3.0) {
                        $PonPort->average_signal = 'Warning';
                    } else {
                        $PonPort->average_signal = 'Very Good';
                    }
                
                    $PonPort->description = $combinedResult->description;
                    $PonPort->tx_power = $combinedResult->tx_power;
                    $PonPort->board = $combinedResult->board;
                    $PonPort->range = $combinedResult->rango_minimo . ' - ' . $combinedResult->rango_maximo;
                    $PonPort->min_range = $combinedResult->rango_minimo;
                    $PonPort->max_range = $combinedResult->rango_maximo;
                    $PonPort->operational_status = $combinedResult->portStatus;
                    $PonPort->olt_id = $id;
                    $PonPort->port = $combinedResult->port;
                
                    // Condiciones para buscar o crear el registro
                    $conditions = [
                        'olt_id' => $id,
                        'board' => $combinedResult->board,
                        'port' => $combinedResult->port,
                    ];
                
                    // Valores para actualizar o crear
                    $values = $PonPort->toArray();
                
                    PonPort::updateOrCreate($conditions, $values);
                }
    
                // Si llegamos aquí sin excepciones, terminamos el bucle
                break;
            } catch (Exception $e) {
                // echo "Error: " . $e->getMessage();
            }
        } while (true); // Bucle infinito
    
        return $combinedResults;
    }

    public function uplinkRegister($id)
    {
        do {
            try {
                $mtu = $this->uplinkData('1.3.6.1.2.1.2.2.1.4', $id);
                $status = $this->uplinkData('1.3.6.1.2.1.2.2.1.8', $id);
                $name = $this->uplinkData('1.3.6.1.2.1.2.2.1.2', $id);
                $admin_status = $this->uplinkData('1.3.6.1.2.1.2.2.1.7', $id);
                $type = $this->uplinkData('1.3.6.1.2.1.2.2.1.3', $id);
                $wavel = $this->uplinkData('1.3.6.1.2.1.2.2.1.5', $id);
                $pivd = $this->pvid($id);
    
                // Asegurémonos de que todos los arrays tengan el mismo tamaño
                $length = max(count($mtu), count($status), count($name), count($admin_status), count($type), count($wavel), count($pivd));
    
                $result = [];
    
                for ($i = 0; $i < $length; $i++) {
                    $result[] = [
                        'olt_id' => $id,
                        'mtu' => $mtu[$i] ?? null,
                        'description' => '',
                        'status' => $status[$i] ?? null,
                        'name' => $name[$i] ?? null,
                        'admin_state' => $admin_status[$i] ?? null,
                        'type' => $type[$i] ?? null,
                        'wavel' => $wavel[$i] ?? null,
                        'pivd_untag' => $pivd[0] ?? null,
                        'negotiation' => $wavel[$i] ?? null,
                    ];
                }
    
                foreach ($result as $data) {
                    uplink::updateOrCreate(['name' => $data['name']], $data);
                }
                // Detener el bucle porque no hay errores
                break;
    
            } catch (Exception $e) {
                // echo "Error: " . $e->getMessage();
            }
        } while (true);
    
        return $result;
    }

    public function oltCardRegister($id)
    {
        $result = [];
    
        do {
            try {
                // Tu código existente para obtener los datos
                $type = $this->getSnmpData('1.3.6.1.4.1.2011.6.3.3.2.1.21', $id);
                $status = $this->getSnmpData('1.3.6.1.4.1.2011.6.3.3.2.1.8', $id);
                $slot = $this->getSnmpData('1.3.6.1.4.1.2011.6.3.3.2.1.1', $id);
                $ports = $this->getSnmpData('1.3.6.1.4.1.2011.6.3.3.3.1.3', $id);
                $software = $this->getSnmpData('1.3.6.1.4.1.2011.6.3.1.3', $id);
                $slots = $this->getSnmpData('1.3.6.1.4.1.2011.6.3.3.3.1.3', $id);
    
                $extractedNumbers = [];
                // Obtener interfaces para los puertos.
                foreach ($slots['oids'] as $oid) {
                    // Realizar una coincidencia de expresiones regulares para extraer el número
                    if (preg_match('/0\.(\d+)\.65535/', $oid, $matches)) {
                        // Convertir el número extraído a entero
                        $extractedNumbers[] = (int)$matches[1];
                    }
                }
    
                // Filtrar los valores de $data, $status, y $slot según los índices en $extractedNumbers
                $filteredType = $this->filterDataByIndices($type, $extractedNumbers);
                $filteredStatus = $this->filterDataByIndices($status, $extractedNumbers);
                $filteredSlot = $this->filterDataByIndices($slot, $extractedNumbers);
                $filteredPorts = $this->filterDataByIndices($ports, $extractedNumbers);
    
                //

                $length = count($extractedNumbers) - 1;

                for ($i = 0; $i < $length; $i++) {
                    $status = $filteredStatus[$i] ?? null;
                
                    $statusText = ($status === 2) ? 'Normal' : (($status === 7) ? 'Offline' : 'Desconocido');
                
                    $dataItem = [
                        'slot' => $filteredSlot[$i] ?? null,
                        'type' => $filteredType[$i] ?? null,
                        'real_type' => $filteredType[$i] ?? null,
                        'ports' => $filteredPorts[$i] ?? null,
                        'software_version' => $software['values'][0] ?? null,
                        'status' => $statusText ?? null,
                        'olt_id' => $id,
                    ];
   
                        // Actualizar o crear el registro en la base de datos
                        OltCard::updateOrCreate(
                            ['slot' => $dataItem['slot'], 'olt_id' => $dataItem['olt_id']],
                            $dataItem
                        );
                
                        $result[] = $dataItem;
                }
                
    
                break;
    
            } catch (Exception $e) {
                // echo "Error: " . $e->getMessage();
            }
        } while (true);
    
        return $result;
    }
    public function vlanRegister($id)
    {
        do {
            try {
                $data = $this->getSnmpData('1.3.6.1.4.1.2011.5.6.1.1.1.1', $id);
                $data2 = $this->getSnmpData('1.3.6.1.4.1.2011.5.6.1.1.1.21', $id);
                $data3 = $this->getSnmpData('1.3.6.1.4.1.2011.5.6.1.1.1.18', $id);
    
                $length = count($data['values']);
    
                $result = [];
    
                for ($i = 0; $i < $length; $i++) {
                    $dataItem = [
                        'vlan_id' => $data['values'][$i] ?? null,
                        'description' => $data2['values'][$i] ?? null,
                        'multicast_vlan' => $data3['values'][$i] ?? null,
                        'management_voip' => false,
                        'dhcp_snooping' => false,
                        'lan_to_lan' => false,
                        'olt_id' => $id,
                    ];
    
                    // Actualizar o crear el registro en la base de datos
                    Vlan::updateOrCreate(['vlan_id' => $dataItem['vlan_id']], $dataItem);
    
                    $result[] = $dataItem;
                }
    
                break;
    
            } catch (Exception $e) {
            //  echo "Error: " . $e->getMessage();
            }

        } while (true);
    
        return $result;
    }

    public function onusData($id)
    {

        
        // $onusStatus = $this->getSnmpData('1.3.6.1.4.1.2011.6.128.1.1.2.46.1.15',$id);
        // $onusModel = $this->getSnmpData('1.3.6.1.4.1.2011.6.128.1.1.2.43.1.8',$id);
        $onusStatus3 = $this->getSnmpData('1.3.6.1.4.1.2011.6.128.1.1.2.43.1.9',$id);

      

        return [$onusStatus3]; 
    }

    private function uplinkData($oids, $id)
    {
        $snmp = $this->getSnmpClient($id);
        $arrayMtu = [];
        $arrayMtuValue = [];
    
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
    
                $arrayMtu[] = $oid->getOid();
                $arrayMtuValue[] = $oid->getValue()->getValue();
            } catch (Exception $e) {
                // Manejar errores al recuperar OIDs
                $arrayMtu[] = ['value' => 'Error al recuperar OID. ' . $e->getMessage()];
            }
        }
    
        // Filtrar solo la parte final después del último punto que tenga una longitud de 9
        $filteredResults = array_filter(array_map(function ($oid, $value) {
            $interfaz = substr(strrchr($oid, '.'), 1);
            return strlen($interfaz) == 9 ? $value : null;
        }, $arrayMtu, $arrayMtuValue));
    
        // Eliminar valores nulos
        $filteredResults = array_filter($filteredResults);
    
        return array_values($filteredResults);
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
    public function ActiveOnus($id)
    {
        // Obtener la información de los Onus por puerto
        $onusData = $this->OnusByPort($id);

        // Obtener la información de los puertos activos
        $activePorts = $this->ActiveOnusByPort($id);

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

    public function ActiveOnusByPort($id)
    {
        $snmp = $this->getSnmpClient($id);
        
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

    public function OnusByPort($id)
    {
        $snmp = $this->getSnmpClient($id);

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

    public function portName($id)
    {
        $snmp = $this->getSnmpClient($id);
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
    
    public function powerTxOLT($id)
    {
        $snmp = $this->getSnmpClient($id);
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
    public function portType($id)
    {
        $snmp = $this->getSnmpClient($id);
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
    
    public function portStatus($id)
    {
        $snmp = $this->getSnmpClient($id);
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
    
    public function pvid($id)
    {
        $snmp = $this->getSnmpClient($id);
        $pvidArray = [];
        
        // OID base para el walk
        $baseOid = '1.3.6.1.4.1.2011.5.6.1.25.1.44';
        
        // Realizar el SNMP walk
        $walk = $snmp->walk($baseOid);
        
        // Iterar a través de las OIDs obtenidas durante el walk
        while ($walk->hasOids()) {
            try {
                $oid = $walk->next();
                $value = $oid->getValue()->getValue();
                
    
                // Agregar el resultado al arreglo asociativo
                $pvidArray[] = $value;
            } catch (Exception $e) {
                // Manejar errores al recuperar OIDs
                $pvidArray[] = ['status' => 'Error al recuperar OID. ' . $e->getMessage()];
            }
        }
    
        return $pvidArray;
    }
    private function filterDataByIndices($data, $indices)
    {
        $filteredData = [];
        foreach ($indices as $index) {
            if (isset($data['values'][$index - 1])) {
                $filteredData[] = $data['values'][$index - 1];
            }
        }
        return $filteredData;
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
