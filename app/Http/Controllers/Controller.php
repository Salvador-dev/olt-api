<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Tenant;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // public function __construct(Request $request){

    //     $idTenant = $request->header('X-Tenant');

    //     if ($idTenant) {
    //         $tenant = Tenant::find($idTenant);

    //         tenancy()->initialize($tenant);
    //     }

    // }

}
