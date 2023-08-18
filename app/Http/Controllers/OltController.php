<?php

namespace App\Http\Controllers;

use App\Models\Olt;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class OltController extends Controller
{
    //
    public function getData()
    {
        $data = Cache::get('olts');
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
            'oltIp' => 'required'
        ]);

        $data = DB::table('olts')->insert([
            'name' => $request['name'],
            'oltIp' => $request['oltIp'],
            'telnet_username' => $request['telnet_username'],
            'telnet_password' => $request['telnet_password'],
            'snmp_read_only' => $request['snmp_read_only'],
            'snmp_read_write' => $request['snmp_read_write'],
            'snmp_udp_port' => $request['snmp_udp_port'],
            'telnet_ssh_tcp_port' => $request['telnet_ssh_tcp_port'],
            'ipvt' => $request['ipvt'],
            'oltHardwareVersion' => $request['oltHardwareVersion'],
            'oltSwVersion' => $request['oltSwVersion'],
            'support_pon_type' => $request['support_pon_type'],
        ]);

        return response()->json(['data' => $data], 200);
    }

    public function show($id)
    {
        $olts = Cache::get('olts');
        $data = array();

        $filter = Arr::where($olts, function ($value, $key) use ($id) {
            return $value->id == $id;
        });

        $data = array_merge($data, $filter);
        return response()->json(['data' => $data], 200);
    }

    public function update(Request $request, $id)
    {
        $data = DB::table('olts')->where('idOlt', $id)->update([
            'name' => $request['name'],
            'oltIp' => $request['oltIp'],
            'telnet_username' => $request['telnet_username'],
            'telnet_password' => $request['telnet_password'],
            'snmp_read_only' => $request['snmp_read_only'],
            'snmp_read_write' => $request['snmp_read_write'],
            'snmp_udp_port' => $request['snmp_udp_port'],
            'telnet_ssh_tcp_port' => $request['telnet_ssh_tcp_port'],
            'ipvt' => $request['ipvt'],
            'oltHardwareVersion' => $request['oltHardwareVersion'],
            'oltSwVersion' => $request['oltSwVersion'],
            'support_pon_type' => $request['support_pon_type'],
        ]);
        return response()->json(['data' => $data], 200);
    }

    public function destroy($id)
    {
        $data = DB::table('olts')->where('idOlt', $id)->delete();
        return response()->json(['data' => $data], 200);
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
