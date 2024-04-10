<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant;

class AuthController extends Controller
{


    public function test(){
        return 'hola';  
    }

    // Metodo para registros
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer'], 200);
    }

    // Metodo para login

    public function login(Request $request)
    {

        if (!Auth::attempt($request->only('email', 'password'))) {


            return response()->json(['message' => 'Email o contraseña invalidos']);
        }

        $user = User::where('email', $request->only('email'))->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        $roles = $user->getRoleNames(); // Returns a collection

        if ($roles->isNotEmpty()) {

            $user->{'role'} = $roles[0];

        } else {

            $user->{'role'} = '';

        }

        unset($user->roles);

        return response()->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer'], 200);


    }

    //Metodo para cambiar contraseña
    public function changePassword(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8',
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        // Obtener el usuario autenticado
        $userId = $request->id;

        $user = User::find($userId);


        if (!$user) {
            return back()->with('error', 'Usuario no encontrado');
        }


        // Verificar si la contraseña actual es correcta
        if (!Hash::check(trim($request->current_password), $user->password)) {
            return back()->with('error', 'La contraseña actual es incorrecta');
        }

        // Encriptar y actualizar la contraseña
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Redireccionar con un mensaje de éxito
        return response()->json(['¡Contraseña actualizada exitosamente!'], 200);
    }

    public function getAllUsers()
    {
        $users = User::select('id', 'name', 'email', 'created_at', 'updated_at')->get();
        return $users;
    }

    // Metodo para logout
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function assignRole(Request $request)
    {

        $user = User::find($request->id);

        if (!$user) {
            return back()->with('error', 'Usuario no encontrado');
        }

        $roles = $user->getRoleNames(); // Returns a collection

        if ($roles->isNotEmpty()) {

            $user->removeRole($roles[0]);

        }

        $user->assignRole($request->input('role'));

        return response()->json(['¡Rol asignado exitosamente!'], 200);
    }
}
