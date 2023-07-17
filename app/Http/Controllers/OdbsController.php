<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OdbsController extends Controller
{
    //
    public function getData()
    {
        $data = DB::table('zones')
                ->join('odbs', 'zones.idZone', '=', 'odbs.zone_id')    
                ->select('odbs.idOdb', 'zones.name as zone', 'odbs.numPorts', 'odbs.name')
                ->get();
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
        $data = DB::table('odbs')->where('idOdb', $id)->get();
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
