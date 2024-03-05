<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    // Método para obtener todos los roles
    public function index()
    {

        // $roles = Role::all()->pluck('name');

        $roles = Role::all();

        return $roles;
    }

    // Metodo para crear un rol nuevo
    public function store(Request $request)
    {
        $role = new Role;
        $role->name = $request->input('name');
        $role->save();

        // $role = Role::create(['name' => $request->input('name')]);

        return response()->json($role, 201);
    }

    // Método para obtener un rol por su ID
    public function show($id)
    {

        $role = Role::findOrFail($id);

        if (!$role) {
            return back()->with('error', 'Rol no encontrado');
        }

        return $role;
    }

    // Método para actualizar un rol NOT SUPORTED The PATCH method is not supported for route api/role/11. Supported methods: GET, HEAD, DELETE.",
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        if (!$role) {
            return back()->with('error', 'Rol no encontrado');
        }

        $role->name = $request->input('name');
        $role->save();

        return response()->json($role, 200);
    }

    // Método para eliminar un rol
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if (!$role) {
            return back()->with('error', 'Rol no encontrado');
        }

        $role->delete();

        return response()->json('Rol eliminado', 200);
    }
}
