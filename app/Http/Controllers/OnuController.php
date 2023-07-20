<?php

namespace App\Http\Controllers;

use App\Models\Onu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OnuController extends Controller
{
    //
    public function getData()
    {
        $data = DB::table('onus')
            ->join('olts', 'onus.olt_id', 'olts.idOlt')
            ->leftJoin('zones', 'onus.zone_id', 'zones.idZone')
            ->leftJoin('odbs', 'onus.odb_id', 'odbs.idOdb')
            ->leftJoin('onuTypes', 'onus.onu_type_id', 'onuTypes.idOnuType')
            ->select('onus.*', 'olts.name as olt', 'zones.name as zone', 'odbs.name as odb', 'onu')
            ->get();
        return response()->json(['data' => $data], 200);
    }

    public function paginater()
    {
        $data = DB::table('onus')
            ->join('olts', 'onus.olt_id', 'olts.idOlt')
            ->leftJoin('zones', 'onus.zone_id', 'zones.idZone')
            ->leftJoin('odbs', 'onus.odb_id', 'odbs.idOdb')
            ->select('onus.*', 'olts.name as olt', 'zones.name as zone', 'odbs.name as odb')
            ->paginate(10);
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

    public function showByOlt($id)
    {
        $data = DB::table('onus')
            ->join('olts', 'onus.olt_id', 'olts.idOlt')
            ->leftJoin('zones', 'onus.zone_id', 'zones.idZone')
            ->leftJoin('odbs', 'onus.odb_id', 'odbs.idOdb')
            ->select('onus.*', 'olts.name as olt', 'zones.name as zone', 'odbs.name as odb')
            ->where('olts.idOlt', $id)
            ->get();
        return response()->json(['data' => $data], 200);
    }
}
