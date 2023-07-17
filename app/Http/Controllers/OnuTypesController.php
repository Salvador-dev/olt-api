<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OnuTypesController extends Controller
{
    //
    public function getData()
    {
        $data = DB::table('onu_types')
            ->leftJoin('capabilitys', 'onu_types.capability_id', 'capabilitys.idCapability')
            ->select('onu_types.idOnuType', 'onu_types.name', 'capabilitys.name as capability', 'onu_types.voipPorts', 'onu_types.ponType', 'onu_types.ethernetPorts', 'onu_types.wifi', 'onu_types.catv', 'onu_types.customProfile')
            ->get();
        return response()->json(['data' => $data], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'capability_id' => 'required|max:255'
        ]);

        $data = DB::table('onu_types')->insert([
            'name' => $request['name'],
            'capability_id' => $request['capability_id'],
            'ponType' => $request['ponType'],
            'ethernetPorts' => $request['ethernetPorts'],
            'wifi' => $request['wifi'],
            'voipPorts' => $request['voipPorts'],
            'catv' => $request['catv'],
            'customProfile' => $request['customProfile'],
            'ethernetPortsPrefix' => $request['ethernetPortsPrefix'],
            'wifiPrefix' => $request['wifiPrefix'],
            'voipPortsPrefix' => $request['voipPortsPrefix']
        ]);

        return response()->json(['data' => $data], 200);
    }

    public function show($id)
    {
        $data = DB::table('onu_types')->where('idOnuType', $id)->get();
        return response()->json(['data' => $data], 200);
    }

    public function update(Request $request, $id)
    {
        $data = DB::table('onu_types')->where('idOnuType', $id)->update([
            'name' => $request['name'],
            'capability_id' => $request['capability_id'],
            'ponType' => $request['ponType'],
            'ethernetPorts' => $request['ethernetPorts'],
            'wifi' => $request['wifi'],
            'voipPorts' => $request['voipPorts'],
            'catv' => $request['catv'],
            'customProfile' => $request['customProfile'],
            'wifiPrefix' => $request['wifiPrefix'],
            'voipPortsPrefix' => $request['voipPortsPrefix']
        ]);
        return response()->json(['data' => $data], 200);
    }

    public function destroy($id)
    {
        $data = DB::table('onu_types')->where('idOnuType', $id)->delete();
        return response()->json(['data' => $data], 200);
    }
}
