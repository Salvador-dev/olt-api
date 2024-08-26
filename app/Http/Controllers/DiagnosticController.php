<?php

namespace App\Http\Controllers;

use App\Models\Diagnostic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiagnosticController extends Controller
{
    public function index(Request $request)
    {

        $search = $request->input("search") ?? null;
        $status = $request->input("status") ?? null;
        $signal = $request->input("signal") ?? null;
        $oltName = $request->input("oltName") ?? null;
        $zoneName = $request->input("zone") ?? null;
        $onuType = $request->input("onuType") ?? null;
        $ponType = $request->input("ponType") ?? null;
        $board = (string) $request->input("board") ?? null;
        $port = (string) $request->input("port") ?? null;
        $odb = $request->input("odb") ?? null;
        $speedProfile = $request->input("speedProfile") ?? null;
        $orderBy = $request->input("orderBy") ?? 'DESC';
        $pageOffset = $request->input("pageOffset") ?? 10;

        $data = DB::table('diagnostics')->join('onus', 'diagnostics.onu_id', 'onus.id')
            ->where('onus.speed_profile_id', '!=',  null)
            ->join('signal', 'diagnostics.signal_id', 'signal.id')
            ->join('status', 'diagnostics.status_id', 'status.id')
            ->join('zones', 'onus.zone_id', 'zones.id')
            ->join('odbs', 'onus.odb_id', 'odbs.id')
            ->join('onu_types', 'onus.onu_type_id', 'onu_types.id')
            ->join('olts', 'onus.olt_id', 'olts.id')
            ->join('speed_profiles', 'speed_profiles.id', 'onus.speed_profile_id')
            ->select(
                'diagnostics.id',
                'status.description as status',
                'signal.description as signal',
                'signal_value as signal_value',
                'diagnostics.distance',
                'onus.name as name',
                'onus.serial as serial',
                'zones.name as zone',
                'odbs.name as odb',
                'diagnostics.updated_at as last_change'
            );

        $data = $data->orderBy('diagnostics.created_at', $orderBy);
        // $data = $data->oldest('reports.created_at');

        if ($status) {
            $data = $data->where('status.description', $status);
        }

        if ($signal) {
            $data = $data->where('signal.description', $signal);
        }

        if ($oltName) {
            $data = $data->where('olts.name', 'LIKE', "%$oltName%");
        }

        if ($ponType) {
            $data = $data->where('pon_types.name', 'LIKE', "%$ponType%");
        }

        if ($board) {
            $data = $data->where('onus.board', $board);
        }

        if ($port) {
            $data = $data->where('onus.port', $port);
        }

        if ($zoneName) {
            $data = $data->where('zones.name', 'LIKE', "%$zoneName%");
        }

        if ($onuType) {
            $data = $data->where('onu_types.name', 'LIKE', "%$onuType%");
        }

        if ($odb) {
            $data = $data->where('odbs.name', 'LIKE', "%$odb%");
        }

        if ($speedProfile) {
            $data = $data->where('speed_profiles.name', 'LIKE', "%$speedProfile%");
        }

        if ($search) {

            if(strtolower($search) == 'api'){
                $data = $data->where('reports.user_id', null);
            } else {

                $data = $data->where('diagnostics.signal_value', 'LIKE', "%$search%")->orWhere('onus.name', 'LIKE', "%$search%")->orWhere('onus.serial', 'LIKE', "%$search%")->orWhere('diagnostics.distance', 'LIKE', "%$search%");
            }
        }

        $data = $data->paginate($pageOffset);
        
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'action' => 'required|max:255',
        ]);

        $data = Diagnostic::create([
            'signal_value' => $request['signal_value'],
            'distance' => $request['distance'],
            'onu_id' => $request['onu_id'],    
            'signal_id' => $request['signal_id'],        
            'status_id' => $request['status_id'],        
        ]);

        return response()->json(['data' => $data], 200);
    }

    
    public function update(Request $request, $id)
    {
      
        $data = Diagnostic::findOrFail($id);

        if (!$data) {
            return back()->with('error', 'Registro no encontrado');
        }

        $data->update($request->all());

        return response()->json(['data' => $data], 200);
    }

    public function destroy($id)
    {
        $data = DB::table('diagnostics')->where('id', $id)->delete();
        return response()->json(['data' => $data], 200);
    }
}
