<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
     // Método para obtener todos los usuarios
     public function index()
     {
         return User::all();
     }
 
     // Método para crear un nuevo usuario
     public function store(Request $request)
     {
         $user = new User;
         $user->name = $request->input('name');
         $user->email = $request->input('email');
         $user->password = bcrypt($request->input('password'));
         $user->save();
 
         return response()->json($user, 201);
     }
 
     // Método para obtener un usuario por su ID
     public function show($id)
     {
         return User::findOrFail($id);
     }
 
     // Método para actualizar un usuario
     public function update(Request $request, $id)
     {
         $user = User::findOrFail($id);
         $user->name = $request->input('name');
         $user->email = $request->input('email');
         $user->password = bcrypt($request->input('password'));
         $user->save();
 
         return response()->json($user, 200);
     }
 
     // Método para eliminar un usuario
     public function destroy($id)
     {
         $user = User::findOrFail($id);
         $user->delete();
 
         return response()->json(null, 204);
     }
}
