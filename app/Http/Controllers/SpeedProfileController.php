<?php

namespace App\Http\Controllers;

use App\Models\SpeedProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpeedProfileController extends Controller
{
    //
    public function getData()
    {
        $data = SpeedProfile::with('onus')->get();
        return response()->json(['data' => $data], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'speed' => 'required',
            'type_conexion' => 'required',
        ]);

        $data = DB::table('speed_profiles')->insert([
            'name' => $request['name'],
            'type_conexion' => $request['type_conexion'],
            'type_speed' => $request['type_speed'],
            'speed' => $request['speed'],
            'prefix' => $request['prefix'],
            'is_default' => false,
        ]);

        return response()->json(['data' => $data], 200);
    }

    public function show($id)
    {
        $data = DB::table('speed_profiles')->where('idSpeedProfile', $id)->get();
        return response()->json(['data' => $data], 200);
    }

    public function update(Request $request, $id)
    {
        $data = DB::table('speed_profiles')->where('idSpeedProfile', $id)->update([
            'name' => $request['name'],
            'onu_id' => $request['onu_id'],
            'type_conexion' => $request['type_conexion'],
            'type_speed' => $request['type_speed'],
            'speed' => $request['speed'],
            'prefix' => $request['prefix'],
        ]);
        return response()->json(['data' => $data], 200);
    }

    public function destroy($id)
    {
        $data = DB::table('speed_profiles')->where('idSpeedProfile', $id)->delete();
        return response()->json(['data' => $data], 200);
    }
}
