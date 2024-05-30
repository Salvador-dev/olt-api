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
        $online = Onu::join('diagnostics', 'diagnostics.onu_id', 'onus.id')->join('status', 'diagnostics.status_id', 'status.id')->where('status.description', 'Online')->count();

        $pending = Onu::where('speed_profile_id', null)->count();
        
        $offline = Onu::join('diagnostics', 'diagnostics.onu_id', 'onus.id')->join('status', 'diagnostics.status_id', 'status.id')->where('status.description', 'Offline')->count();

        $low_signal = Onu::join('diagnostics', 'diagnostics.onu_id', 'onus.id')->join('signal', 'diagnostics.signal_id', 'signal.id')->where('signal.description', '!=', 'Very good')->count(); 

        return response()->json(['online' => $online, 'pending' => $pending, 'offline' => $offline, 'low_signal' => $low_signal], 200);
    }

    public function showByOlt($olt_id)
    {
        $onus = Onu::where('olt_id', $olt_id)->get();
        $online = $onus->where('status', 'Online')->count();
        $pending = $onus->where('administrative_status', 'Disabled')->count();
        $offline = $onus->where('status', 'Offline')->count();
        $signal = $onus->where('signal', '!=', 'Very good')->count();
        return response()->json(['online' => $online, 'pending' => $pending, 'offline' => $offline, 'low_signal' => $signal], 200);
    }
}
