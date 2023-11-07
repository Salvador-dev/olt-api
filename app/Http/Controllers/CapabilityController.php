<?php

namespace App\Http\Controllers;

use App\Models\Capability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CapabilityController extends Controller
{
    public function getData()
    {
        $data = Capability::select('id', 'name')->get();
        return response()->json(['data' => $data], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255'
        ]);
        $data = Capability::create([
            'name' => $request['name'],
        ]);
        return response()->json(['data' => $data], 200);
    }

    public function show($id)
    {

        $zone = DB::table('capabilitys')
        ->select('capabilitys.id', 'capabilitys.name')
        ->where('capabilitys.id', $id)
        ->get();

        $data = ['data' => $zone];
        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $data = Capability::findOrFail($id);
        $data->update([
            'name' => $request->name
        ]);
        return response()->json(['data' => $data], 200);
    }

    public function destroy($id)
    {
        $data = Capability::findOrFail($id);
        $data->delete();
        return response()->json(['data' => $data], 200);
    }
}
