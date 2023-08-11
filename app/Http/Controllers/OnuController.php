<?php

namespace App\Http\Controllers;

use App\Models\Onu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class OnuController extends Controller
{
    //
    public function getData()
    {
        $data = array();
        $onus = Cache::get('onus');

        foreach ($onus as $onu) {
            $data = array_merge($data, $onu);
        }

        return response()->json(['data' => $data], 200);
    }

    public function paginater()
    {
        $data = DB::table('onus')
            ->join('olts', 'onus.olt_id', 'olts.idOlt')
            ->leftJoin('zones', 'onus.zone_id', 'zones.idZone')
            ->leftJoin('odbs', 'onus.odb_id', 'odbs.idOdb')
            ->select('onus.*', 'olts.name as olt', 'zones.name as zone', 'odbs.name as odb', DB::raw("CONCAT(olts.name, ' ',onus.pon_type, ' ', onus.name) as onu"))
            ->paginate(10);
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        // puertos por defecto para cada ONU
        $ports = [
            [
                "port" => "eth_0/1",
                "status" => true,
                "mode" => "LAN",
                "dhcp" => true
            ],
            [
                "port" => "eth_0/2",
                "status" => true,
                "mode" => "LAN",
                "dhcp" => true
            ],
            [
                "port" => "eth_0/3",
                "status" => true,
                "mode" => "LAN",
                "dhcp" => true
            ],
            [
                "port" => "eth_0/4",
                "status" => true,
                "mode" => "LAN",
                "dhcp" => true
            ]
        ];

        $json = json_encode($ports);

        $data = Onu::create([
            'autoincrement' => $request['autoincrement'],
            'onu_external' => $request['onu_external'],
            'pon_type' => $request['pon_type'],
            'sn' => $request['sn'],
            'onu_type' => $request['onu_type'],
            'name' => $request['name'],
            'olt_id' => $request['olt_id'],
            'board' => $request['board'],
            'port' => $request['port'],
            'allocated_onu' => $request['allocated_onu'],
            'zone_id' => $request['zone_id'],
            'address' => $request['address'],
            'lat' => $request['lat'],
            'lng' => $request['lng'],
            'odb_id' => $request['odb_id'],
            'mode' => $request['mode'],
            'wam_mode' => $request['wam_mode'],
            'ip_address' => $request['ip_address'],
            'subnet_mask' => $request['subnet_mask'],
            'default_gateway' => $request['default_gateway'],
            'dns1' => $request['dns1'],
            'dns2' => $request['dns2'],
            'username' => $request['username'],
            'password' => $request['password'],
            'catv' => $request['catv'],
            'administrative_status' => $request['administrative_status'],
            'auth_date' => $request['auth_date'],
            'status' => $request['status'],
            'signal' => $request['signal'],
            'signal_1310' => $request['signal_1310'],
            'signal_1490' => $request['signal_1490'],
            'distance' => $request['distance'],
            'service_port' => $request['service_port'],
            'service_port_vlan' => $request['service_port_vlan'],
            'service_port_cvlan' => $request['service_port_cvlan'],
            'service_port_svlan' => $request['service_port_svlan'],
            'service_port_tag_transform_mode' => $request['service_port_tag_transform_mode'],
            'speed_up_id' => $request['speed_up_id'],
            'speed_download_id' => $request['speed_download_id'],
        ]);

        $dataPorts = DB::table('onu_ports')->insert([
            'onu_id' => $data->id,
            'data_ports' => $json,
        ]);

        return response()->json(['data' => $data], 200);
    }

    public function show($id)
    {
        $data = DB::table('onus')
            ->join('olts', 'onus.olt_id', 'olts.idOlt')
            ->leftJoin('zones', 'onus.zone_id', 'zones.idZone')
            ->leftJoin('odbs', 'onus.odb_id', 'odbs.idOdb')
            ->leftJoin('speed_profiles as speedUp', 'onus.speed_up_id', 'speedUp.idSpeedProfile')
            ->leftJoin('speed_profiles as speedDownload', 'onus.speed_download_id', 'speedDownload.idSpeedProfile')
            ->leftJoin('onu_types', 'onus.onu_type', 'onu_types.idOnuType')
            ->where('onus.id', $id)
            ->select('onus.*', 'olts.name as olt', 'zones.name as zone', 'odbs.name as odb', 'speedUp.name as speed_up_name', 'speedDownload.name as speed_download_name')
            ->get();

        //$data = Onu::where('id', $id)->with(['olt', 'speedProfileUp', 'speedProfileDownload', 'zone', 'ports'])->first();

        return response()->json(['data' => $data], 200);
    }

    public function update(Request $request, $id)
    {
        $data = DB::table('onus')->where('id', $id)->update([
            'name' => $request['name'],
        ]);
        return response()->json(['data' => $data], 200);
    }

    public function destroy($id)
    {
        $data = DB::table('onus')->where('id', $id)->delete();
        return response()->json(['data' => $data], 200);
    }

    public function showByOlt($id)
    {
        $data = DB::table('onus')
            ->join('olts', 'onus.olt_id', 'olts.idOlt')
            ->leftJoin('zones', 'onus.zone_id', 'zones.idZone')
            ->leftJoin('odbs', 'onus.odb_id', 'odbs.idOdb')
            ->select('onus.*', 'olts.name as olt', 'zones.name as zone', 'odbs.name as odb')
            ->where('olts.idOlt', $id)
            ->get();
        return response()->json(['data' => $data], 200);
    }
}
