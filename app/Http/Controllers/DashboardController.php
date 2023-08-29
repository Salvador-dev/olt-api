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
        $signal = $onus->where('status', 'Offline')->count();
        $data = array(['online' => $online], ['pending' => $pending], ['signal' => $signal]);
        return response()->json(['data' => $data], 200);
    }
}
