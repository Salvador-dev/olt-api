<?php

namespace App\Http\Controllers;

use App\Models\Olt;
use App\Models\OltCard;
use App\Models\HardwareVersion;
use App\Models\SoftwareVersion;
use App\Models\Uplink;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class OltController extends Controller
{
    //
    public function getData()
    {
        $data = Olt::join('hardware_versions', 'olts.olt_hardware_version_id', 'hardware_versions.id')
            ->select(
                'olts.id',
                'olts.name',
                'hardware_versions.name as hardware_version',
                'hardware_versions.id as hardware_version_id',
                'olts.ip',
                'olts.telnet_port',
                'olts.snmp_udp_port'
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
        $olt = Olt::where('olts.id', $id)
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
            Olt::where('id', $id)->delete();
            return response()->json(['data' => 'Success!'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    //Debe recibir ID del olt en cuestion
    public function getOltTemperature($id)
    {

        $client = new \GuzzleHttp\Client();
        $request = new \GuzzleHttp\Psr7\Request('GET', env('API_URL') . '/olt/get_tiempoactivo_temperatura/'.$id);
        $res = $client->sendAsync($request)->wait();
        $res = json_decode($res->getBody(), true);
        $data = $res;
        return response()->json(['data' => $data], 200);
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

    public function getUplinks($id){
         // Intenta obtener datos desde la caché
    $cachedData = Cache::get('uplinks_data_' . $id);

    if ($cachedData) {
        // Si los datos están en caché, devuélvelos
        return response()->json(['data' => $cachedData], 200);
    }

        try {
            $client = new \GuzzleHttp\Client();
            $request = new \GuzzleHttp\Psr7\Request('GET', env('API_URL') . '/olt/get_uplinks/'.$id);
            $res = $client->sendAsync($request)->wait();
            $data = json_decode($res->getBody());
    
            $response = array_map(function ($item) use (&$index) {
                return [
                    'type' => $item->tipo_puerto ?? null,
                    'slot' => $item->slot ?? null,
                    'puerto' => $item->puerto ?? null,
                    'vlans' => $item->vlans ?? null,
                    'negociation' => $item->negociacion ?? null,
                    'description' => $item->descripcion ?? null,
                    'status' => $item->estado_operacinal ?? null,
                    'PVID_Untag' => $item->PVID_untag ?? null,
                    'admin_state' => $item->estado_administrativo?? null,
                    
                ];
            }, $data);
            Cache::put('uplinks_data_' . $id, $response, 3600);

            return response()->json(['data' => $response], 200);
        
        } catch (Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }
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
    public function getPONType($id)
    {
        // Intenta obtener datos desde la caché
        $cachedData = Cache::get('PONType_data_' . $id);
    
        if ($cachedData) {
            // Si los datos están en caché, devuélvelos
            return response()->json(['data' => $cachedData], 200);
        }
    
        try {
            // Si los datos no están en caché, realiza la solicitud a la API
            $client = new \GuzzleHttp\Client();
            $request = new \GuzzleHttp\Psr7\Request('GET', env('API_URL') . '/olt/get_puertos/' . $id);
            $res = $client->sendAsync($request)->wait();
            $data = json_decode($res->getBody());
    
            $response = array_map(function ($item) {
                return [
                    'slot' => $item->slot ?? null,
                    'port' => $item->puerto ?? null,
                    'type' => $item->tipo_puerto ?? null,
                    'status' => $item->estado_operacinal ?? null,
                    'admin_state' => $item->estado_administrativo ?? null,
                    'tx_power' => $item->poder_tx ?? null,
                    'description' => $item->descripcion ?? null,
                    'cantidad_onus' => $item->cantidad_onus ?? null,
                    'cantidad_online_onus' => $item->cantidad_online_onus ?? null,
                    'rango_maximo' => $item->rango_maximo ?? null,
                    'rango_minimo' => $item->rango_minimo ?? null,
                ];
            }, $data);
    
            Cache::put('PONType_data_' . $id, $response, 3600);
    
            return response()->json(['data' => $response], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
}