<?php

namespace App\Http\Controllers\super_admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function getTenants(){
        
        $data = Tenant::all();

        return response()->json(['data' => $data], 200);
    }
}
