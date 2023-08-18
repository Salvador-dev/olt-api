<?php

namespace App\Http\Controllers;

use App\Models\Onu;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class OnuController extends Controller
{
    //
    public function getData()
    {
        $data = Cache::get('onus');
        return response()->json(['data' => $data], 200);
    }

    public function onusUnconfigureds()
    {
        $data = Cache::get('onusUnconfigured');
        return response()->json(['data' => $data], 200);
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
        $onus = Cache::get('onus');
        $data = array();

        $filter = Arr::where($onus, function ($value, $key) use ($id) {
            return $value->unique_external_id == $id;
        });

        $data = array_merge($data, $filter);

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
        $onus = Cache::get('onus');
        $data = array();

        $filter = Arr::where($onus, function ($value, $key) use ($id) {
            return $value->olt_id == $id;
        });

        $data = array_merge($data, $filter);

        return response()->json(['data' => $data], 200);
    }

    public function getOnuFullStatus($extenal_id)
    {

        try {
            $client = new Client();
            $request = new Request('GET', env('API_URL2') . '/get_onu_full_status_info/' . $extenal_id);
            $res = $client->sendAsync($request)->wait();
            $res = json_decode($res->getBody(), true);
            $res = json_decode($res[0]);
            $data = $res->full_status_info;
        } catch (Exception $e) {
            return response()->json(['error' => $e], 500);
        }

        return response()->json(['data' => $data], 200);
    }

    public function getOnuRunningConfig($extenal_id)
    {
        try {
            $client = new Client();
            $request = new Request('GET', env('API_URL2') . '/get_running_config/' . $extenal_id);
            $res = $client->sendAsync($request)->wait();
            $res = json_decode($res->getBody(), true);
            $res = json_decode($res[0]);
            $data = $res->running_config;
        } catch (Exception $e) {
            return response()->json(['error' => $e], 500);
        }

        return response()->json(['data' => $data], 200);
    }
}
