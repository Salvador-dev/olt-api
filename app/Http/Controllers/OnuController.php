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
            ->leftJoin('onu_types', 'onus.onu_type', 'onu_types.idOnuType')
            ->select('onus.*', 'olts.name as olt', 'zones.name as zone', 'odbs.name as odb')
            ->get();
        return response()->json(['data' => $data], 200);
    }

    public function paginater()
    {
        $data = DB::table('onus')
            ->join('olts', 'onus.olt_id', 'olts.idOlt')
            ->leftJoin('zones', 'onus.zone_id', 'zones.idZone')
            ->leftJoin('odbs', 'onus.odb_id', 'odbs.idOdb')
            ->select('onus.*', 'olts.name as olt', 'zones.name as zone', 'odbs.name as odb', DB::raw("CONCAT(olts.name, ' ',onus.pon_type, ' ', onus.name) as onu"))
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
        $data = DB::table('onus')
            ->join('olts', 'onus.olt_id', 'olts.idOlt')
            ->leftJoin('zones', 'onus.zone_id', 'zones.idZone')
            ->leftJoin('odbs', 'onus.odb_id', 'odbs.idOdb')
            ->leftJoin('speed_profiles', 'onus.speed_profile_id', 'speed_profiles.idSpeedProfile')
            ->leftJoin('onu_types', 'onus.onu_type', 'onu_types.idOnuType')
            ->where('onus.id', $id)
            ->select('onus.*', 'olts.name as olt', 'zones.name as zone', 'odbs.name as odb', 'speed_profiles.name as speed')
            ->get();
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
