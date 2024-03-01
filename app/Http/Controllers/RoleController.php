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
        // $role = new Role;
        // $role->name = $request->input('name');
        // $role->save();

        $role = Role::create(['name' => $request->input('name')]);

        return response()->json($role, 201);
    }
}
