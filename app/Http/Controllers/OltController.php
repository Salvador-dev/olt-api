<?php

namespace App\Http\Controllers;

use App\Models\Olt;
use App\Models\OltCard;
use App\Models\HardwareVersion;
use App\Models\SoftwareVersion;
use App\Models\Uplink;
use App\Models\PonPort;
use App\Models\Vlan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\OltTemperature;
use FreeDSx\Snmp\SnmpClient;
use Illuminate\Support\Facades\DB;

class OltController extends Controller
{

    public function getData()
    {
        $data = DB::table('olts')->join('hardware_versions', 'olts.olt_hardware_version_id', 'hardware_versions.id')
            ->select(
                'olts.id',
                'olts.name',
                'hardware_versions.name as hardware_version',
                'olts.ip',
                'olts.telnet_port',
                'olts.snmp_udp_port',
                'olts.olt_active'
            )
            ->orderBy('olts.id')
            ->get();
    
        return response()->json(['data' => $data], 200);
    }
    

    public function paginater()
    {
        $data = Olt::with('onus')->paginate(2);
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'ip' => 'required',
            'olt_hardware_version_id' => 'required',
            'telnet_port' => 'required',
            'snmp_udp_port' => 'required',
        ]);

        $data = Olt::create([
            'name' => $request->name,
            'olt_hardware_version_id' => $request->olt_hardware_version_id,
            'olt_software_version_id' => $request->olt_software_version_id,
            'ip' => $request->ip,
            'telnet_port' => $request->telnet_port,
            'telnet_username' => $request->telnet_username,
            'telnet_password' => $request->telnet_password,
            'snmp_read_only' => $request->snmp_read_only,
            'snmp_read_write' => $request->snmp_read_write,
            'snmp_udp_port' => $request->snmp_udp_port,
            'ipvt_module' => $request->ipvt_module,
            'pon_type_id' => $request->pon_type_id,
        ]);

        return response()->json(['data' => $data], 200);
    }

    public function show($id)
    {
        $olt = DB::table('olts')->where('olts.id', $id)
            ->join(
                'hardware_versions',
                'olts.olt_hardware_version_id',
                'hardware_versions.id'
            )
            ->select(
                'olts.*',
                'hardware_versions.name as hardware_version'
            )
            ->first();
        $olt_cards = OltCard::where('olt_id', $id)->get();
        $olt['olt_cards'] = $olt_cards;
        $olt_uplinks = Uplink::where('olt_id', $id)->get();
        $olt['olt_uplinks'] = $olt_uplinks;
        $olt_ports = PonPort::where('olt_id', $id)->get();
        $olt['olt_ports'] = $olt_ports;
        $olt_vlan = Vlan::where('olt_id', $id)->get();
        $olt['olt_vlan'] = $olt_vlan;

        return response()->json(['data' => $olt], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'ip' => 'required',
            'olt_hardware_version_id' => 'required',
            'telnet_port' => 'required',
            'snmp_udp_port' => 'required',
        ]);

        $data = Olt::where('id', $id)->update([
            'name' => $request->name,
            'olt_hardware_version_id' => $request->olt_hardware_version_id,
            'olt_software_version_id' => $request->olt_software_version_id,
            'ip' => $request->ip,
            'telnet_port' => $request->telnet_port,
            'telnet_username' => $request->telnet_username,
            'telnet_password' => $request->telnet_password,
            'snmp_read_only' => $request->snmp_read_only,
            'snmp_read_write' => $request->snmp_read_write,
            'snmp_udp_port' => $request->snmp_udp_port,
            'ipvt_module' => $request->ipvt_module,
            'pon_type_id' => $request->pon_type_id,
        ]);

        return response()->json(['data' => $data], 200);
    }

    public function destroy($id)
    {
        try {
            $olt = Olt::findOrFail($id);

            $olt->uplink()->delete();
            $olt->vlans()->delete();
            $olt->olt_cards()->delete();
            $olt->pon_ports()->delete();
            $olt->onus()->delete();
            $olt->delete();
         
            return response()->json(['data' => 'Success!'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    //Debe recibir ID del olt en cuestion
    public function getOltTemperature()
    {
        try {
            // Obtener todos los registros de olt_temperature con información relacionada de olts
            $oltTemperatures = OltTemperature::with('olt')->get();
    
            // Construir la respuesta JSON
            $response = $oltTemperatures->map(function ($oltTemperature) {
                return [
                    'olt_id' => $oltTemperature->olt_id,
                    'olt_name' => $oltTemperature->olt->name,
                    'uptime' => $oltTemperature->uptime,
                    'env_temp' => $oltTemperature->env_temp,
                    'created_at' => $oltTemperature->created_at->toDateTimeString(), // Agregado
                ];
            });
    
            return response()->json($response);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

    public function getHardware(){
        $hardwareVersions = HardwareVersion::select('id', 'name')->get();

        return response()->json(['data' => $hardwareVersions], 200);
    }
    public function getSoftware()
    {
        $softwareVersions = SoftwareVersion::select('id', 'name')->get();
    
        return response()->json(['data' => $softwareVersions], 200);
    }

    public function getUplinks($id) {
        $uplinks = Uplink::where('olt_id', $id)
            ->select('type', 'admin_state', 'status', 'negotiation', 'pivd_untag', 'description', 'mode_vlan')
            ->get();
    
        // Devolver los resultados de la consulta
        return response()->json(['data'=> $uplinks], 200) ;
    }

    public function getVlans($id)
    {
        // Intenta obtener datos desde la caché
        $cachedData = Cache::get('vlans_data_' . $id);
    
        if ($cachedData) {
            // Si los datos están en caché, devuélvelos
            return response()->json(['data' => $cachedData], 200);
        }
    
        try {
            // Si los datos no están en caché, realiza la solicitud a la API
            $client = new \GuzzleHttp\Client();
            $request = new \GuzzleHttp\Psr7\Request('GET', env('API_URL') . '/olt/get_Vlans/' . $id);
            $res = $client->sendAsync($request)->wait();
            $data = json_decode($res->getBody());
    
            $response = array_map(function ($item) {
                return [
                    'id' => isset($item->id) ? $item->id : null, 
                    'alcance' => $item->alcance ?? null,
                    'Description' => $item->descripcion ?? null,
                    'vlan' => $item->vlan ?? null,
                    'olt' => $item->olt ?? null,
                ];
            }, $data);
    
            Cache::put('vlans_data_' . $id, $response, 3600);
    
            return response()->json(['data' => $response], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getPONPort($id)
    {
        // Obtener todos los registros que coincidan con el olt_id dado
        $ponPorts = PonPort::where('olt_id', $id)->get();

        // Verificar si se encontraron registros
        if ($ponPorts->isEmpty()) {
            return response()->json(['message' => 'No se encontraron registros para el olt_id proporcionado'], 404);
        }


        $transformedPonPorts = $ponPorts->map(function ($ponPort) {
            return [
                'board' => $ponPort->board,
                'pon_type' => $this->mapearPonType($ponPort->pon_type_id),
                'admin_status' => $this->mapearAdminStatus($ponPort->admin_status),
                'operational_status' => $this->mapearOperationalStatus($ponPort->operational_status),
                'onus' => $ponPort->onus,
                'onus_active' => $ponPort->onus_active,
                'average_signal' => $ponPort->average_signal,
                'range' => $ponPort->range,
                'min_range' => $ponPort->min_range,
                'max_range' => $ponPort->max_range,
                'tx_power' => $ponPort->tx_power,
                'description' => $ponPort->description,
            ];
        });

        // Retornar todos los detalles de los registros transformados en la respuesta JSON
        return response()->json(['data' => $transformedPonPorts],200);
    }


         // Método para mapear el tipo PON a una cadena legible
    private function mapearPonType($ponTypeId)
    {
        switch ($ponTypeId) {
            case 1:
                return 'GPON';
            case 2:
                return 'EPON';
            case 3:
                return 'GPON | EPON';
            default:
                return 'Desconocido';
        }
    }

     // Métodos adicionales para mapear admin_status y operational_status
    private function mapearAdminStatus($adminStatus)
    {
        switch ($adminStatus) {
            case 1:
                return 'UP';
            case 2:
                return 'DOWN';
            case 3:
                return 'Desconocido';
            default:
                return 'Desconocido';
        }
    }

    private function mapearOperationalStatus($operationalStatus)
    {
        switch ($operationalStatus) {
            case 1:
                return 'Enabled';
            case 2:
                return 'Disabled';
            default:
                return 'Desconocido';
        }
    }
    
}