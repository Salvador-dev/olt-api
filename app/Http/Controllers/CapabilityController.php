<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CapabilityController extends Controller
{
    public function getData()
    {
        $data = DB::table('capabilitys')->select('idCapability', 'name')->get();
        return response()->json(['data' => $data], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255'
        ]);

        $data = DB::table('capabilitys')->insert([
            'name' => $request['name'],
        ]);

        return response()->json(['data' => $data], 200);
    }

    public function show($id)
    {
        $data = DB::table('capabilitys')->where('idCapability', $id)->get();
        return response()->json(['data' => $data], 200);
    }

    public function update(Request $request, $id)
    {
        $data = DB::table('capabilitys')->where('idCapability', $id)->update([
            'name' => $request['name'],
        ]);
        return response()->json(['data' => $data], 200);
    }

    public function destroy($id)
    {
        $data = DB::table('capabilitys')->where('idCapability', $id)->delete();
        return response()->json(['data' => $data], 200);
    }
}
