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
