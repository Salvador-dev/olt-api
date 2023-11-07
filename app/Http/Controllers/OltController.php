<?php

namespace App\Http\Controllers;

use App\Models\Olt;
use App\Models\OltCard;
use App\Models\Uplink;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OltController extends Controller
{
    //
    public function getData()
    {
    $data = Olt::join('hardware_versions', 'olt_hardware_version_id', 'hardware_versions.id')
        ->join('software_versions', 'olt_software_version_id', 'software_versions.id')
        ->select('olts.id', 'olts.name', 'hardware_versions.name as hardware_version', 'software_versions.name as software_version', 'olts.ip', 'olts.telnet_port', 'olts.snmp_udp_port', 'olts.ipvt_module')
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
            'oltHardwareVersion' => 'required',
            'telnet_ssh_tcp_port' => 'required',
            'snmp_port' => 'required',
        ]);

        $data = Olt::create([
            'name' => $request->name,
            'olt_hardware_version_id' => $request->oltHardwareVersion,
            'olt_software_version_id' => $request->oltSwVersion,
            'ip' => $request->ip,
            'telnet_port' => $request->telnet_ssh_tcp_port,
            'telnet_username' => $request->telnet_username,
            'telnet_password' => $request->telnet_password,
            'snmp_read_only' => $request->snmp_read_only,
            'snmp_read_write' => $request->snmp_read_write,
            'snmp_udp_port' => $request->snmp_port,
            'ipvt_module' => $request->ipvt,
            'pon_type_id' => $request->pon_type_id,
        ]);

        return response()->json(['data' => $data], 200);
    }
    public function show($id)
    {
        $olt = Olt::where('olts.id', $id)
            ->join('hardware_versions', 'olts.olt_hardware_version_id', 'hardware_versions.id')
            ->join('software_versions', 'olt_software_version_id', 'software_versions.id')
            ->select(
                'olts.*',
                'hardware_versions.name as hardware_version',
                'software_versions.name as software_version'
            )
            ->first();

             // $olt_cards = OltCard::where('olt_id', $id)->get();
        // $olt_uplinks = Uplink::where('olt_id', $id)->get();
    
        // // Organiza los datos en un arreglo asociativo
        // $response = [
        //     'olt' => $olt,
        //     'olt_cards' => $olt_cards,
        //     'olt_uplinks' => $olt_uplinks
        // ];
    
        $data = ['data' => [$olt]];

        return response()->json($data, 200);
    }
    
        
       

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'ip' => 'required',
            'oltHardwareVersion' => 'required',
            'telnet_ssh_tcp_port' => 'required',
            'snmp_port' => 'required',
        ]);

        $data = Olt::where('id', $id)->update([
            'name' => $request->name,
            'olt_hardware_version_id' => $request->oltHardwareVersion,
            'olt_software_version_id' => $request->oltSwVersion,
            'ip' => $request->ip,
            'telnet_port' => $request->telnet_ssh_tcp_port,
            'telnet_username' => $request->telnet_username,
            'telnet_password' => $request->telnet_password,
            'snmp_read_only' => $request->snmp_read_only,
            'snmp_read_write' => $request->snmp_read_write,
            'snmp_udp_port' => $request->snmp_port,
            'ipvt_module' => $request->ipvt,
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

    public function getOltTemperature()
    {
        /*         $client = new Client();
        $request = new Request('GET', env('API_URL') . '/get_olts_uptime_and_env_temperature');
        $res = $client->sendAsync($request)->wait();
        $res = $res->getBody(); */

        $data = [];

        return response()->json(['data' => $data], 200);
    }
}
