<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Método para obtener todos los usuarios
    public function index()
    {
        $users = User::all();

        foreach ($users as $user) {

            $roles = $user->getRoleNames(); // Returns a collection

            if($roles->isNotEmpty()){
    
                $user->{'role'} = $roles[0];
    
            } else {

                $user->{'role'} = '';

            }

            unset($user->roles);

        }       

        return $users;
    }

    // Método para crear un nuevo usuario
    public function store(Request $request)
    {
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        if($request->input('role')){

            $user->assignRole($request->input('role'));

        } else {

            $user->assignRole('consulta');

        }

        return response()->json($user, 201);
    }

    // Método para obtener un usuario por su ID
    public function show($id)
    {

        $user = User::findOrFail($id);

        $roles = $user->getRoleNames(); // Returns a collection
        
        if($roles->isNotEmpty()){
    
            $user->{'role'} = $roles[0];

        } else {

            $user->{'role'} = '';

        }

        unset($user->roles);

        if (!$user) {
            return back()->with('error', 'Usuario no encontrado');
        }

        return $user;
    }

    // Método para roles de un usuario
    public function getRoles($id)
    {

        $user = User::findOrFail($id);

        $roles = $user->getRoleNames(); // Returns a collection
        
        if (!$user) {
            return back()->with('error', 'Usuario no encontrado');
        }

        return $roles;
    }

    // Método para permisos de un usuario
    public function getPermissions($id)
    {

        $user = User::findOrFail($id);

        $permissions = $user->getPermissionsViaRoles()->pluck('name'); // Returns a collection

        if (!$user) {
            return back()->with('error', 'Usuario no encontrado');
        }

        return $permissions;
    }

    // Método para actualizar un usuario
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (!$user) {
            return back()->with('error', 'Usuario no encontrado');
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if($request->input('password')){
            $user->password = bcrypt($request->input('password'));        
        }
        $user->save();

        $roles = $user->getRoleNames(); // Returns a collection 

        if($roles->isNotEmpty()){

            $user->removeRole($roles[0]);

        }

        $user->assignRole($request->input('role'));

        return response()->json($user, 200);
    }

    // Método para eliminar un usuario
    public function destroy($id)
    {
        $user = User::findOrFail($id);


        if (!$user) {
            return back()->with('error', 'Usuario no encontrado');
        }

        $roles = $user->getRoleNames(); // Returns a collection

        if($roles->isNotEmpty()){

            $user->removeRole($roles[0]);

        }

        $user->delete();

        return response()->json('Usuario eliminado', 200);
    }
}
