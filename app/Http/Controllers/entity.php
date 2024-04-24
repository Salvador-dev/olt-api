<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Facades\Tenancy;

class entity extends Controller
{
   

    public function registered(Request $request) {
        

        $id = $request->id;
        $email = $request->email;
        $password = $request->password;
        $company_fullname = $request->company_fullname;
        $rif = $request->rif;
        $description = $request->description;
        $address = $request->address;
        $phone = $request->phone;
        

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
        
        return response()->json(['created company successfully' => $id], 200);
    }
    
   

    public function show($id)
    {
        $exists = (bool) Tenant::where('id', $id)->exists();
        
        // Devolver una respuesta JSON con el resultado
        return response()->json(['company' => $exists], 200);
    }
    

}
