<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OnuTypesController extends Controller
{
    //
    public function getData()
    {
        $data = Cache::get('onu_types');
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
        $onu_types = Cache::get('onu_types');
        $data = array();

        $filter = Arr::where($onu_types, function ($value, $key) use ($id) {
            return $value->id == $id;
        });

        $data = array_merge($data, $filter);
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
