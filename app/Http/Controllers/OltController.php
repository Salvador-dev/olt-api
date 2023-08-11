<?php

namespace App\Http\Controllers;

use App\Models\Olt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
        $data = Olt::where('idOlt',$id)->with('onus')->first();
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

    public function search($query)
    {
        $data = DB::table('olts')->where('name', 'LIKE', '%'. $query. '%')->get();
        return response()->json(['data' => $data], 200);
    }
}
