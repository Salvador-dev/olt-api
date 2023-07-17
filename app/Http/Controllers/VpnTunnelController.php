<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VpnTunnelController extends Controller
{
    //
    public function getData()
    {
        $data = DB::table('vpntunnels')->get();
        return response()->json(['data' => $data], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tunnelName' => 'required|max:255',
        ]);

        $data = DB::table('vpntunnels')->insert([
            'tunnelSubnet' => $request['tunnelSubnet'],
            'tunnelName' => $request['tunnelName'],
            'tunnelPassword' => $request['tunnelPassword'],
            'tunnelRoutes' => $request['tunnelRoutes'],
        ]);

        return response()->json(['data' => $data], 200);
    }

    public function show($id)
    {
        $data = DB::table('vpntunnels')->where('idVpnTunnel', $id)->get();
        return response()->json(['data' => $data], 200);
    }

    public function update(Request $request, $id)
    {
        $data = DB::table('vpntunnels')->where('idVpnTunnel', $id)->update([
            'tunnelSubnet' => $request['tunnelSubnet'],
            'tunnelName' => $request['tunnelName'],
            'tunnelPassword' => $request['tunnelPassword'],
            'tunnelRoutes' => $request['tunnelRoutes'],
        ]);
        return response()->json(['data' => $data], 200);
    }

    public function destroy($id)
    {
        $data = DB::table('vpntunnels')->where('idVpnTunnel', $id)->delete();
        return response()->json(['data' => $data], 200);
    }
}
