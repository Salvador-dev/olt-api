<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OdbsController extends Controller
{
    //
    public function getData()
    {
        $data = Cache::get('odbs');
        return response()->json(['data' => $data], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'zone_id' => 'required',
            'numPorts' => 'required'
        ]);

        $data = DB::table('odbs')->insert([
            'name' => $request['name'],
            'zone_id' => $request['zone_id'],
            'numPorts' => $request['numPorts'],
            'lat' => $request['lat'],
            'lng' => $request['lng'],
        ]);

        return response()->json(['data' => $data], 200);
    }

    public function show($id)
    {
        $odbs = Cache::get('odbs');
        $data = array();

        $filter = Arr::where($odbs, function ($value, $key) use ($id) {
            return $value->id == $id;
        });

        $data = array_merge($data, $filter);
        return response()->json(['data' => $data], 200);
    }

    public function update(Request $request, $id)
    {
        $data = DB::table('odbs')->where('idOdb', $id)->update([
            'name' => $request['name'],
            'zone_id' => $request['zone_id'],
            'numPorts' => $request['numPorts'],
            'lat' => $request['lat'],
            'lng' => $request['lng'],
        ]);
        return response()->json(['data' => $data], 200);
    }

    public function destroy($id)
    {
        $data = DB::table('odbs')->where('idOdb', $id)->delete();
        return response()->json(['data' => $data], 200);
    }
}
