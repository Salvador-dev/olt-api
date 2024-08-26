<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\BillingHistory;
use App\Models\Olt;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{

    public function index()
    {

        $data = DB::table('billings')->leftJoin('olts', 'billings.olt_id', '=', 'olts.id')->leftJoin('subscription_status', 'billings.subscription_status_id', '=', 'subscription_status.status_id')->select(
            'billings.id as id',
            'olts.name as olt_name',
            'billings.monthly_price as monthly_price_id',
            'subscription_status.description as subscription_status',
            'subscription_end_date'
        )->get();
        
        return response()->json(['data' => $data], 200);
    }

    public function history(Request $request)
    {
        $search = $request->input("search") ?? null;
        $oltName = $request->input("oltName") ?? null;
        $fromDate = $request->input("fromDate") ?? null;
        $toDate = $request->input("toDate") ?? null;
        $orderBy = $request->input("orderBy") ?? 'DESC';
        $pageOffset = $request->input("pageOffset") ?? 10;


        $data = DB::table('billing_history')->leftJoin('billings', 'billing_history.billing_id', '=','billings.id')
        ->leftJoin('olts', 'billings.olt_id', '=', 'olts.id')
        ->leftJoin('users', 'billing_history.user_id', '=', 'users.id')
        ->leftJoin('subscription_status', 'billings.subscription_status_id', '=', 'subscription_status.id')
        ->select(
            'olts.name as olt_name',
            'transaction_id as transaction_no.',
            'users.email as user',
            'months_paid',
            'billing_history.created_at as date'
        );

        $data = $data->orderBy('billing_history.created_at', $orderBy);
        // $data = $data->oldest('reports.created_at');

        if ($oltName) {
            $data = $data->where('olts.name', 'LIKE', "%$oltName%");
        }

        if($fromDate){

            $data = $data->where('billing_history.created_at', '>=', $fromDate . ' 00:00:00');

        }

        if($toDate){

            $data = $data->where('billing_history.created_at', '<=', $toDate . ' 00:00:00');

        }

        if ($search) {
            $data = $data->where('transaction_id', 'LIKE', "%$search%")->orWhere('users.email', 'LIKE', "%$search%");
        
        }

        $data = $data->paginate($pageOffset);
        
        return response()->json($data, 200);
    }

    public function store(Request $request){
        $data = Billing::create([
            'olt_id' => $request['olt_id'],
            'monthly_price' => $request['monthly_price'],
            'subscription_status_id' => $request['subscription_status_id'],
            'subscription_end_date' => $request['subscription_end_date']
        ]);

        return response()->json(['data' => $data], 200);
    }

    public function update(Request $request, $id)
    {
        $data = Billing::findOrFail($id);

        if (!$data) {
            return back()->with('error', 'Factura no encontrado');
        }

        if($request['olt_id']){
            $data->olt_id = $request['olt_id'];
        }

        if($request['monthly_price']){
            $data->monthly_price = $request['monthly_price'];
        }

        if($request['subscription_status_id']){
            $data->subscription_status_id = $request['subscription_status_id'];
        }

        if($request['subscription_end_date']){

            $timestamp = Carbon::parse()->format($request['subscription_end_date']);

            $data->subscription_end_date = $timestamp;
        }

        $data->save();

        return response()->json(['data' => $data], 200);
    }


    public function storeHistory(Request $request)
    {
        $data = BillingHistory::create([
            'billing_id' => $request['billing_id'],
            'transaction_id' => $request['transaction_id'],
            'user_id' => $request['user_id'],
            'months_paid' => $request['months_paid'],
        ]);

        return response()->json(['data' => $data], 200);
    }

}
