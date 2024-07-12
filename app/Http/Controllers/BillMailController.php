<?php

namespace App\Http\Controllers;

use App\Mail\BillMail;
use App\Models\BillingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class BillMailController extends Controller
{
    public function sendBill(Request $request)
    {

        if(BillingHistory::where('transaction_id', $request->input('transaction_id'))->exists()){

            $billings = BillingHistory::where('transaction_id', $request->input('transaction_id'))
            ->join('billings', 'billings.id', 'billing_history.billing_id')
            ->join('olts', 'billings.olt_id', 'olts.id')
            ->select(
                'olts.name as item_name',
                'billings.monthly_price as item_price',
                'billing_history.months_paid as item_quantity'
            )->get();
            
            $total_amount = 0;

            foreach($billings as $billing){

                $total_amount += $billing->item_price * $billing->item_quantity;

            }

            $data = [
                'subject' => "Fibex OLT Billing",
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'city' => $request->input('city'),
                'companyCode' => $request->input('companyCode'),
                'companyName' => $request->input('companyName'),
                'country' => $request->input('country'),
                'email' => $request->input('email'),
                'state' => $request->input('state'),
                'telephone' => $request->input('telephone'),
                'zipCode' => $request->input('zipCode'),
                'transaction_id' => $request->input('transaction_id'),
                'order_date' => $request->input('order_date'),
                'items' => $billings,
                'total_amount' => $total_amount
            ];
    
            Mail::to($request->input('email'))->send(new BillMail($data));
    
            return "MENSAJE ENVIADO";

        } else {

            return "No Items Found";
        }
     
    }
}
