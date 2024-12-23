<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Facades\Tenancy;

class entity extends Controller
{
   

    public function registered(Request $request) {

        try {

            $id = $request->id;
            $email = $request->email;
            $password = $request->password;
            $company_fullname = $request->company_fullname;
            $rif = $request->rif;
            $description = $request->description;
            // $address = $request->address;
            $phone = $request->phone;
    
            $tenants = DB::select('select * from login_emails where id = ?', [$id]);
            $emails = DB::select('select * from login_emails where email = ?', [$email]);
    
            if(empty($tenants) && empty($emails)){

                // DB::beginTransaction();
    
                $tenant = Tenant::create([
                    'id' => $id,
                    'company_fullname' => $company_fullname,
                    'rif' => $rif,
                    // 'address' => $address,
                    'description' => $description,
                    'phone' => $phone,
                    'email' => $email,
                ]);
            
                $tenant->domains()->create(['domain' => $id]);
            
                Tenancy::find($id)->run(function ($tenant) use ($id, $email, $password) {
                    $user = User::create([
                        'name' => $id,
                        'email' => $email,
                        'password' => Hash::make($password)
                    ]);
                    $user->assignRole('admin');
                });
    
                // DB::commit();

                DB::connection('mysql')->insert('insert into login_emails (email, company) values (?, ?)', [$email, $id]);

                return response()->json(['data' => $id, 'message' => 'created company successfully'], 200);
    
            } else {
    
                return response()->json(['message' => 'El email o la empresa ya han sido registrados'], 500);
    
            }

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
         
    }

    public function logout(Request $request){

        tenancy()->end();

        Auth::logout();
        
        return response()->json(['Logout successfully'], 200);

    }

    public function login(Request $request){

        $tenant = DB::select('select * from login_emails where email = ?', [$request->email]);

        $response = ['token_type' => 'Bearer'];

        if(isset($tenant[0]) && $tenant[0]->company != 'admin'){

            tenancy()->initialize($tenant[0]->company);
            
            if (!Auth::attempt($request->only('email', 'password'))) {

                return response()->json(['message' => 'Email o contraseña invalidos']);
            }
    
            $user = User::where('email', $request->only('email'))->firstOrFail();

            $response['company'] = $tenant[0]->company;

        } else {

            $user = User::where('email', $request->only('email'))->first();

            if($user) {
                if (Auth::attempt($request->only('email', 'password'))) {
                    $response['company'] = 'admin';
                } else {
                    return response()->json(['message' => 'Email o contraseña invalidos']);
                } 
            } else {
                return response()->json(['message' => 'Email o contraseña invalidos']);
            }

        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $response['access_token'] = $token;
    
        $roles = $user->getRoleNames(); // Returns a collection

        if ($roles->isNotEmpty()) {

            $user->{'role'} = $roles[0];

        } else {

            $user->{'role'} = '';

        }

        unset($user->roles);

        $response['data'] = $user;

        return response()->json($response, 200);

    }
    
   

    public function show($id)
    {
        $exists = (bool) Tenant::where('id', $id)->exists();
        
        // Devolver una respuesta JSON con el resultado
        return response()->json(['company' => $exists], 200);
    }
    

}
