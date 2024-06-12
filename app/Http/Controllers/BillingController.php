<?php

namespace App\Http\Controllers;

use App\Models\Olt;
use Illuminate\Http\Request;

class BillingController extends Controller
{

    public function index()
    {
        $olts = Olt::select(
            'olts.id',
            'olts.name',
            'olts.olt_active'
        )
        ->orderBy('olts.id')
        ->get();

        $data = [];
    
        foreach ($olts as $olt) {
            
            array_push($data, ['olt_name' => $olt['name'], 'subscription_status' => 'Active', 'subscription_end_date' => '20-Aug-2024']);
            
        }

        return response()->json(['data' => $data], 200);
    }

}
