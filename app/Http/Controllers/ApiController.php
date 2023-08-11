<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    //
    public function getData()
    {
        $data = Cache::get('zones');
        return response()->json(['data' => $data], 200);
    }

    // Pendiente por corregir
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255'
        ]);

        $data = DB::table('zones')->insert([
            'name' => $request['name'],
        ]);

        return response()->json(['data' => $data], 200);
    }

    public function show($id)
    {
        $zones = Cache::get('zones');
        $data = array();
        
        $filter = Arr::where($zones, function ($value, $key) use ($id) {
            return $value->id == $id;
        });
        
        $data = array_merge($data, $filter);
        return response()->json(['data' => $data], 200);
    }

    // Pendiente por corregir
    public function update(Request $request, $id)
    {
        $data = DB::table('zones')->where('idZone', $id)->update([
            'name' => $request->name,
        ]);
        return response()->json(['data' => $data], 200);
    }

    // Pendiente por corregir
    public function destroy($id)
    {
        $data = DB::table('zones')->where('idZone', $id)->delete();
        return response()->json(['data' => $data], 200);
    }
}
