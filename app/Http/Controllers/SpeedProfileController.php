<?php

namespace App\Http\Controllers;

use App\Models\SpeedProfile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SpeedProfileController extends Controller
{
    //
    public function getData()
    {
        $data = SpeedProfile::select('id', 'name', 'speed', 'direction', 'type_conexion', 'use_prefix')->get();
        return response()->json(['data' => $data], 200);
    }

    // Pendiente por corregir
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'speed' => 'required',
            'type_conexion' => 'required',
        ]);
    
        $speedProfile = new SpeedProfile();
        $speedProfile->name = $request->input('name');
        $speedProfile->type_conexion = $request->input('type_conexion');
        $speedProfile->direction = $request->input('type_speed');
        $speedProfile->speed = $request->input('speed');
        $speedProfile->use_prefix = $request->input('use_prefix');
        $speedProfile->save();
    
        return response()->json(['data' => $speedProfile], 200);
    }
    

    public function show($id)
    {
        // $speed_profiles = Cache::get('speed_profiles');
        // $data = array();

        // $filter = Arr::where($speed_profiles, function ($value, $key) use ($id) {
        //     return $value->id == $id;
        // });

        // $data = array_merge($data, $filter);

        $data = DB::table('speed_profiles')
        ->select('speed_profiles.id', 'speed_profiles.name', 'speed_profiles.use_prefix', 'speed_profiles.type_conexion', 'speed_profiles.speed', 'speed_profiles.direction' )
        ->where('speed_profiles.id', $id)
        ->get();
        return response()->json(['data' => $data], 200);
    }

    // Pendiente por corregir
    public function update(Request $request, $id)
    {
        
        $data = DB::table('speed_profiles')->where('id', $id)->update([
            'name' => $request['name'],
            // 'onu_id' => $request['onu_id'],
            'type_conexion' => $request['type_conexion'],
            'direction' => $request['type_speed'],
            'speed' => $request['speed'],
            'use_prefix' => $request['use_prefix'],
        ]);
        return response()->json(['data' => $data], 200);
    }

    // Pendiente por corregir
    public function destroy($id)
    {
        $data = DB::table('speed_profiles')->where('id', $id)->delete();
        return response()->json(['data' => $data], 200);
    }
}
