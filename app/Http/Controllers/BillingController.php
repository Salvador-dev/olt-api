<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\BillingHistory;
use App\Models\Olt;
use Illuminate\Http\Request;

class BillingController extends Controller
{

    public function index()
    {
        $data = Billing::join('olts', 'billings.olt_id', 'olts.id')
        ->join('subscription_status', 'billings.subscription_status_id', 'subscription_status.id')
        ->select(
            'olts.name as olt_name',
            'billings.monthly_price as monthly_price_id',
            'subscription_status.description as subscription_status',
            'subscription_end_date'
        )->get();
        
        return response()->json(['data' => $data], 200);
    }

    public function history()
    {
        $data = BillingHistory::join('billings', 'billing_history.billing_id', 'billings.id')
        ->join('olts', 'billings.olt_id', 'olts.id')
        ->join('users', 'billing_history.user_id', 'users.id')
        ->join('subscription_status', 'billings.subscription_status_id', 'subscription_status.id')
        ->select(
            'olts.name as olt_name',
            'transaction_id',
            'users.email as user',
            'amount',
            'billing_history.created_at as date'
        )->get();

        return response()->json(['data' => $data], 200);
    }

}
