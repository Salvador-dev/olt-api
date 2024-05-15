<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {

        $search = $request->input("search") ?? null;
        $oltName = $request->input("oltName") ?? null;
        $fromDate = $request->input("fromDate") ?? null;
        $toDate = $request->input("toDate") ?? null;
        $orderBy = $request->input("orderBy") ?? 'DESC';
        $pageOffset = $request->input("pageOffset") ?? 10;

        $data = Report::join('onus', 'reports.onu_id', 'onus.id')
            ->leftJoin('users', 'reports.user_id', 'users.id')
            ->join('olts', 'onus.olt_id', 'olts.id')
            ->select(
                'reports.id',
                'reports.action',
                'olts.name as olt',
                'onus.name as onu_name',
                'users.email as user',
                'reports.created_at as date'
            );

        $data = $data->orderBy('id', $orderBy);

        if ($search) {

            if(strtolower($search) == 'api'){
                $data = $data->where('reports.user_id', null);
            } else {

                $data = $data->where('reports.action', 'LIKE', "%$search%")->orWhere('onus.name', 'LIKE', "%$search%")->orWhere('users.email', 'LIKE', "%$search%");
            }
        }

        if ($oltName) {
            $data = $data->where('olts.name', 'LIKE', "%$oltName%");
        }

        $data = $data->paginate($pageOffset);
        
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'action' => 'required|max:255',
        ]);

        $data = Report::create([
            'action' => $request['action'],
            'onu_id' => $request['onu_id'],
            'user_id' => $request['user_id'],        
        ]);

        return response()->json(['data' => $data], 200);
    }

    public function destroy($id)
    {
        $data = DB::table('reports')->where('id', $id)->delete();
        return response()->json(['data' => $data], 200);
    }


}
