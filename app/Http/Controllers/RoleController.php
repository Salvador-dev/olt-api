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

        $roles = Role::with("permissions")->get();

        return $roles;
    }

    // Metodo para crear un rol nuevo
    public function store(Request $request)
    {
        $role = new Role;
        $role->name = $request->input('name');
        $role->save();

        $permissions = $request->input('permissions');
        $role->syncPermissions($permissions);

        return response()->json($role, 201);
    }

    // Método para obtener un rol por su ID
    public function show($id)
    {

        $role = Role::findOrFail($id)->with('permissions')->get();

        if (!$role) {
            return back()->with('error', 'Rol no encontrado');
        }

        return $role;
    }

    // Método para actualizar un rol
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        if (!$role) {
            return back()->with('error', 'Rol no encontrado');
        }
        // PENDIENTE ROLES WEB SOLO ACEPTAN PERMISOS WEB, SANCTUM CON SANCTUM
        // SYNC agrega los permisos dados y elimina lo que estaban, acepta id o nombres de permisos
        $role->name = $request->input('name');
        $permissions = $request->input('permissions');
        $role->syncPermissions($permissions);        
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

        $role->syncPermissions([]);
        $role->delete();

        return response()->json('Rol eliminado', 200);
    }
}
