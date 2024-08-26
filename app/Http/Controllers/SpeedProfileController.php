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
        $data = DB::table('speed_profiles')->select('id', 'name', 'upload_speed', 'download_speed', 'type_conexion', 'use_prefix')->get();
        return response()->json(['data' => $data], 200);
    }

    // Pendiente por corregir
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'upload_speed' => 'required',
            'download_speed' => 'required',
            'type_conexion' => 'required',
        ]);

        // TODO: CORREGIR NUEVOS CAMPOS DE SPEED PROFILE CRUD
    
        $speedProfile = new SpeedProfile();
        $speedProfile->name = $request->input('name');
        $speedProfile->type_conexion = $request->input('type_conexion');
        $speedProfile->upload_speed = $request->input('upload_speed');
        $speedProfile->download_speed = $request->input('download_speed');
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

        $data = DB::table('speed_profiles')->select('id', 'name', 'upload_speed', 'download_speed', 'type_conexion', 'use_prefix')
        ->where('speed_profiles.id', $id)
        ->get();
        return response()->json(['data' => $data], 200);
    }

    public function update(Request $request, $id)
    {
        
        $data = DB::table('speed_profiles')->where('id', $id)->update([
            'name' => $request['name'],
            // 'onu_id' => $request['onu_id'],
            'type_conexion' => $request['type_conexion'],
            'upload_speed' => $request['upload_speed'],
            'download_speed' => $request['download_speed'],
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
