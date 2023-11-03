<?php

namespace App\Http\Controllers;

use App\Models\Odb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OdbsController extends Controller
{
    //
    public function getData()
    {
        $data = DB::table('odbs')
            ->join('zones', 'odbs.zone_id', 'zones.id')
            ->select('odbs.id', 'odbs.name', 'odbs.nr_of_ports', 'odbs.latitude', 'odbs.longitude', 'zones.name as zone')
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
            'nr_of_ports' => $request['numPorts'],
            'latitude' => $request['lat'],
            'longitude' => $request['lng'],
        ]);

        return response()->json(['data' => $data], 200);
    }

    public function show($id)
    {
        $data = DB::table('odbs')
            ->join('zones', 'odbs.zone_id', 'zones.id')
            ->select('odbs.id', 'odbs.name', 'odbs.nr_of_ports', 'odbs.latitude', 'odbs.longitude', 'zones.name as zone')
            ->where('odbs.id', $id)
            ->get();
        return response()->json(['data' => $data], 200);
    }

    public function update(Request $request, $id)
    {
        $data = DB::table('odbs')->where('id', $id)->update([
            'name' => $request['name'],
            'zone_id' => $request['zone_id'],
            'nr_of_ports' => $request['numPorts'],
            'latitude' => $request['lat'],
            'longitude' => $request['lng'],
        ]);
        return response()->json(['data' => $data], 200);
    }

    public function destroy($id)
    {
        $data = Odb::find($id);
        $data->delete();
        return response()->json(['data' => $data], 200);
    }
}
