<?php

namespace App\Http\Controllers\super_admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $oldEmail = $data->email;

        $data->id = $request->input('id');
        $data->company_fullname = $request->input('company_fullname');
        $data->email = $request->input('email');
        $data->phone = $request->input('phone');
        $data->rif = $request->input('rif');

        if($request->input('password')){
            $data->password = bcrypt($request->input('password'));        
        }
        $data->save();

        $emails = DB::connection('mysql')->select('select * from login_emails where email = ?', [$oldEmail]);

        if(!empty($emails)){
            DB::connection('mysql')->update('update login_emails set email = ? where email = ?', [$request->email, $oldEmail]);
            DB::connection('mysql')->update('update login_emails set company = ? where email = ?', [$request->id, $oldEmail]);
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
