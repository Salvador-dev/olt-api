<?php

namespace App\Http\Controllers;

use App\Models\OnuType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OnuTypesController extends Controller
{
    //
    public function getData()
    {
        $data = DB::table('onu_types')
            ->join('capabilitys', 'onu_types.capability_id', 'capabilitys.id')
            ->join('pon_types', 'onu_types.pon_type_id', 'pon_types.id')
            ->select(
                'onu_types.id',
                'onu_types.name',
                'pon_types.name as pon_type',
                'capabilitys.name as capability',
                'onu_types.ethernet_ports',
                'onu_types.wifi_ports',
                'onu_types.voip_ports',
                'onu_types.catv',
                'onu_types.allow_custom_profiles'
            )
            ->orderBy('id')
            ->get();

        return response()->json(['data' => $data], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'capability_id' => 'required|max:255'
        ]);

        $data = OnuType::create([
            'name' => $request['name'],
            'capability_id' => $request['capability_id'],
            'pon_type_id' => $request['pon_type_id'],
            'ethernet_ports' => $request['ethernet_ports'],
            'wifi_ports' => $request['wifi_ports'],
            'voip_ports' => $request['voip_ports'],
            'catv' => $request['catv'],
            'allow_custom_profiles' => $request['allow_custom_profiles']
        ]);

        return response()->json(['data' => $data], 200);
    }

    public function show($id)
    {
        $data = DB::table('onu_types')
            ->join('capabilitys', 'onu_types.capability_id', 'capabilitys.id')
            ->join('pon_types', 'onu_types.pon_type_id', 'pon_types.id')
            ->select(
                'onu_types.id',
                'onu_types.name',
                'pon_types.name as pon_type',
                'capabilitys.name as capability',
                'onu_types.ethernet_ports',
                'onu_types.wifi_ports',
                'onu_types.voip_ports',
                'onu_types.catv',
                'onu_types.allow_custom_profiles'
            )
            ->orderBy('id')
            ->where('onu_types.id', $id)
            ->get();
        return response()->json(['data' => $data], 200);
    }

    public function update(Request $request, $id)
    {
        $data = OnuType::findOrFail($id);
        $data->update([
            'name' => $request['name'],
            'capability_id' => $request['capability_id'],
            'pon_type_id' => $request['pon_type_id'],
            'ethernet_ports' => $request['ethernet_ports'],
            'wifi_ports' => $request['wifi_ports'],
            'voip_ports' => $request['voip_ports'],
            'catv' => $request['catv'],
            'allow_custom_profiles' => $request['allow_custom_profiles']
        ]);
        return response()->json(['data' => $data], 200);
    }

    public function destroy($id)
    {
        $data = OnuType::findOrFail($id);
        $data->delete();
        return response()->json(['data' => $data], 200);
    }
}
