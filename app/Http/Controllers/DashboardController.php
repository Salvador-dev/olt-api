<?php

namespace App\Http\Controllers;

use App\Models\Onu;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB; 

class DashboardController extends Controller
{
    //
    public function dashboard()
    {
        // Utilizamos subconsultas para contar cada estado específico
        $statuses = DB::table('onus')
            ->leftJoin('diagnostics', 'diagnostics.onu_id', '=', 'onus.id')
            ->leftJoin('status', 'diagnostics.status_id', '=', 'status.id')
            ->leftJoin('signal', 'diagnostics.signal_id', '=', 'signal.id')
            ->select(
                DB::raw("SUM(CASE WHEN status.description = 'Online' THEN 1 ELSE 0 END) as online"),
                DB::raw("SUM(CASE WHEN status.description = 'Offline' THEN 1 ELSE 0 END) as offline"),
                DB::raw("SUM(CASE WHEN status.description IS NULL THEN 1 ELSE 0 END) as pending"),
                DB::raw("SUM(CASE WHEN signal.description != 'Very good' THEN 1 ELSE 0 END) as low_signal")
            )
            ->first();
    
        return response()->json([
            'online' => $statuses->online,
            'pending' => $statuses->pending,
            'offline' => $statuses->offline,
            'low_signal' => $statuses->low_signal
        ], 200);
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
