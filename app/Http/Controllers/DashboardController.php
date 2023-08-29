<?php

namespace App\Http\Controllers;

use App\Models\Onu;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class DashboardController extends Controller
{
    //
    public function dashboard()
    {
        $onus = Onu::all();
        $online = $onus->where('status', 'Online')->count();
        $pending = $onus->where('administrative_status', 'Disabled')->count();
        $offline = $onus->where('status', 'Offline')->count();
        $signal = $onus->where('signal', '!=', 'Very good')->count();
        $data = array(['online' => $online], ['pending' => $pending], ['offline' => $offline], ['low signal' => $signal]);
        return response()->json(['data' => $data], 200);
    }
}
