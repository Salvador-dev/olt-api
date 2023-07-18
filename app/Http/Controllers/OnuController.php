<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OnuController extends Controller
{
    //
    public function getData()
    {
        $data = DB::table('onus')
            ->join('olts', 'onus.olt_id', 'olts.idOlt')
            ->select('onus.id', 'onus.name', 'olts.name as olt')
            ->get();
        return response()->json(['data' => $data], 200);
    }
    public function paginater()
    {
        $data = DB::table('onus')
            ->join('olts', 'onus.olt_id', 'olts.idOlt')
            ->join('odbs', 'onus.odb_id', 'odbs.idOdb')
            ->join('zones', 'onus.zone_id', 'zones.idZone')
            ->select('onus.id', 'onus.name', 'onus.sn', 'zones.name as zone', DB::raw("CONCAT(olts.name, ' ',onus.onu) as onu"), 'odbs.name as odb')
            ->paginate(8);
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $data = DB::table('onus')->insert([
            'name' => $request['name'],
        ]);

        return response()->json(['data' => $data], 200);
    }

    public function show($id)
    {
        $data = DB::table('onus')->where('id', $id)->get();
        return response()->json(['data' => $data], 200);
    }

    public function update(Request $request, $id)
    {
        $data = DB::table('onus')->where('id', $id)->update([
            'name' => $request['name'],
        ]);
        return response()->json(['data' => $data], 200);
    }

    public function destroy($id)
    {
        $data = DB::table('onus')->where('id', $id)->delete();
        return response()->json(['data' => $data], 200);
    }
}
