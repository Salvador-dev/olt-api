<?php

namespace App\Http\Controllers;

use App\Models\Olt;
use App\Models\OltCard;
use App\Models\HardwareVersion;
use App\Models\SoftwareVersion;
use App\Models\Uplink;
use Exception;
use Illuminate\Http\Request;


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
            'snmp_udp_port' => $request->snmp_port,
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

    public function getOltTemperature()
    {
        /*         $client = new Client();
        $request = new Request('GET', env('API_URL') . '/get_olts_uptime_and_env_temperature');
        $res = $client->sendAsync($request)->wait();
        $res = $res->getBody(); */

        $data = [];

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
}