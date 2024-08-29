<?php

namespace App\Http\Controllers\super_admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Stancl\Tenancy\Facades\Tenancy;

class SuperAdminController extends Controller
{
    public function index(){
        
        $data = Tenant::select(
            'id',
            'company_fullname',
            'rif',
            'phone',
            'email as principal_email',
            'created_at as registration_date'
        )->get();

        foreach ($data as $item) {
            unset($item->data);
        }

        return response()->json(['data' => $data], 200);
    }

    public function showBilling($id)
    {

        $data = [];

        tenancy()->initialize($id);

        $data = DB::table('billings')->leftJoin('olts', 'billings.olt_id', '=', 'olts.id')->leftJoin('subscription_status', 'billings.subscription_status_id', '=', 'subscription_status.status_id')->select(
            'billings.id as id',
            'olts.name as olt_name',
            'billings.monthly_price as monthly_price',
            'subscription_status.description as subscription_status',
            'subscription_end_date'
        )->get();


        tenancy()->end();


        return response()->json(['data' => $data], 200);
    }

    public function billingHistory(Request $request, $id)
    {

        $data = [];

        tenancy()->initialize($id);

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
        
        tenancy()->end();

        return response()->json(["data" =>$data], 200);
    }

    public function billings(){

        $tenants = DB::table('tenants')->select('id as company')->get();

        $data = [];

        foreach ($tenants as $tenant) {

            $tenant->id = $tenant->company;
            $tenant->pending_to_pay = null;
            $tenant->pending_to_process = null;
            $tenant->trial = null;

            Tenancy::find($tenant->company)->run(function ($item) use ($tenant){

                \Illuminate\Support\Facades\Log::debug($item);

                $billings = DB::table('billings')->get();

                \Illuminate\Support\Facades\Log::debug($billings);

                $expiredArray = $billings->filter(function($billing){
                    return $billing->subscription_status_id == 0;
                });

                if($expiredArray->isEmpty()){

                    $processingArray = $billings->filter(function($billing){
                        return $billing->subscription_status_id == 3;
                    });

                    if($processingArray->isEmpty()){

                        $trialArray = $billings->filter(function($billing){
                            return $billing->subscription_status_id == 2;
                        });

                        if($trialArray->isEmpty()){

                            $activeArray = $billings->filter(function($billing){
                                return $billing->subscription_status_id == 3;
                            });

                            if(!$activeArray->isEmpty()){
            
                                $tenant->subscription_status = 'Active';
            
                            }
        
                        } else {
        
                            foreach ($trialArray as $trial) {
                                $tenant->trial += $trial->monthly_price;
                            }
        
                            $tenant->subscription_status = 'Trial';
        
                        }
    
                    } else {
    
                        foreach ($processingArray as $processing) {
                            $tenant->pending_to_process += $processing->monthly_price;
                        }
    
                        $tenant->subscription_status = 'Processing';
    
                    }

                } else {

                    foreach ($expiredArray as $expired) {
                        $tenant->pending_to_pay += $expired->monthly_price;
                    }

                    $tenant->subscription_status = 'Expired';

                }

            });

            array_push($data, $tenant);
        }
    
        return response()->json(['data' => $data], 200);
    }

    public function show($id){
        
        $data = Tenant::find($id);

        if (!$data) {
            return back()->with('error', 'Registro no encontrado');
        }

        return response()->json(['data' => $data], 200);
    }

    public function update(Request $request, $id){

        $data = Tenant::findOrFail($id);

        if (!$data) {
            return back()->with('error', 'Registro no encontrado');
        }   

        $oldEmail = $data->email;

        $data->email = $request->input('email');
        $data->phone = $request->input('phone');

        $email = $request->input('email');
        $password = $request->input('password');

        if($password){
            
            Tenancy::find($id)->run(function ($tenant) use ($id, $email, $password, $oldEmail) {

                $user = User::where('email', $oldEmail)->first();
                
                \Illuminate\Support\Facades\Log::debug($user);


                if (!$user) {
                    return back()->with('error', 'Registro no encontrado');
                }  

                $user->email = $email;
                $user->password = bcrypt($password);     

                $user->save();
            });
        }

        $data->save();

        $emails = DB::connection('mysql')->select('select * from login_emails where email = ?', [$oldEmail]);

        if(!empty($emails)){
            DB::connection('mysql')->update('update login_emails set email = ? where email = ?', [$request->email, $oldEmail]);
        }

        return response()->json($data, 200);
    }

    public function destroy($id){
        $data = Tenant::findOrFail($id);

        if (!$data) {
            return back()->with('error', 'Registro no encontrado');
        }    
    
        $data->delete();

        $emails = DB::connection('mysql')->select('select * from login_emails where company = ?', [$id]);

        if(!empty($emails)){
            DB::connection('mysql')->delete('delete from login_emails where company = ?', [$id]);
        }

        return response()->json('Registro eliminado', 200);
    }
}
