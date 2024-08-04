<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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

        $emails = DB::connection('mysql')->select('select * from login_emails where email = ?', [$request->email]);

        if(empty($emails)){

            tenancy()->initialize($request->company);

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

            $currentDB = DB::connection()->getDatabaseName();
            $tenant = explode('tenant', $currentDB)[1];
            DB::connection('mysql')->insert('insert into login_emails (email, company) values (?, ?)', [$user->email, $tenant]);

            return response()->json($user, 201);

        } else {

            return response()->json('Email ya utilizado', 500);

        }

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

        if (!$user) {
            return back()->with('error', 'Usuario no encontrado');
        }

        $permissions = $user->getPermissionsViaRoles()->pluck('name'); // Returns a collection

        return $permissions;
    }

    // Método para actualizar un usuario
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (!$user) {
            return back()->with('error', 'Usuario no encontrado');
        }

        $oldEmail = $user->email;

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

        $emails = DB::connection('mysql')->select('select * from login_emails where email = ?', [$oldEmail]);

        if(!empty($emails)){
            DB::connection('mysql')->update('update login_emails set email = ? where email = ?', [$request->email, $oldEmail]);
        }

        return response()->json($user, 200);
    }

    // Método para eliminar un usuario
    public function destroy($id)
    {
        $user = User::findOrFail($id);


        if (!$user) {
            return back()->with('error', 'Usuario no encontrado');
        }

        $oldEmail = $user->email;

        $roles = $user->getRoleNames(); // Returns a collection

        if($roles->isNotEmpty()){

            $user->removeRole($roles[0]);

        }

        $user->delete();

        $emails = DB::connection('mysql')->select('select * from login_emails where email = ?', [$oldEmail]);

        if(!empty($emails)){
            DB::connection('mysql')->delete('delete from login_emails where email = ?', [$oldEmail]);
        }

        return response()->json('Usuario eliminado', 200);
    }
}
