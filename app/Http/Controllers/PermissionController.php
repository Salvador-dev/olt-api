<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    // Método para obtener todos los permisos
    public function index()
    {


        $permissions = Permission::all();

        return $permissions;
    }

    // Metodo para crear un permiso nuevo
    public function store(Request $request)
    {
        $permission = new Permission;
        $permission->name = $request->input('name');
        $permission->save();

        // $role = Role::create(['name' => $request->input('name')]);

        return response()->json($permission, 201);
    }

    // Método para obtener un permiso por su ID
    public function show($id)
    {

        $permission = Permission::findOrFail($id);

        if (!$permission) {
            return back()->with('error', 'Permiso no encontrado');
        }

        return $permission;
    }

    // Método para actualizar un permiso
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        if (!$permission) {
            return back()->with('error', 'Permiso no encontrado');
        }

        $permission->name = $request->input('name');
        $permission->save();

        return response()->json($permission, 200);
    }

    // Método para eliminar un permiso
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);

        if (!$permission) {
            return back()->with('error', 'Permiso no encontrado');
        }

        $permission->delete();

        return response()->json('Permiso eliminado', 200);
    }
}
