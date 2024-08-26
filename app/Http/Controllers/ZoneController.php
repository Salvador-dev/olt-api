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
        // Usando el método all() ya que es más simple y directo
        $data = Zone::select('id', 'name')->get();

        return response()->json(['data' => $data], 200);
    }

    // Método para almacenar una nueva zona
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        // Crea una nueva zona con el nombre recibido
        $zone = Zone::create(['name' => $request->name]);

        return response()->json(['data' => $zone], 200);
    }

    // Método para mostrar una zona específica
    public function show($id)
    {
        // Usamos Eloquent con findOrFail para obtener un solo registro o lanzar 404 si no existe
        $zone = Zone::select('id', 'name')->findOrFail($id);

        return response()->json(['data' => $zone], 200);
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
