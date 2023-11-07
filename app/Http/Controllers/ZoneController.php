<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZoneController extends Controller
{
    //
    public function getData()
    {
        $data = Zone::select('id', 'name')->get();
        return response()->json(['data' => $data], 200);
    }

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
        $zone = Zone::findOrFail($id);
        $data = ['data' => [$zone]];
    
        return response()->json($data, 200);
    }
    

    public function update(Request $request, $id)
    {
        $zone = Zone::findOrFail($id);
        $zone->update([
            'name' => $request->name,
        ]);
        return response()->json(['data' => $zone], 200);
    }

    public function destroy($id)
    {
        $zone = Zone::findOrFail($id);
        $zone->delete();
        return response()->json(['data' => $zone], 200);
    }
}
