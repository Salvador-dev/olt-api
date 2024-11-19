<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Str;

class StripeController extends Controller
{
    public function stripeCheckout(Request $request){

        $stripeSecretKey = '';
        $companyId = $request->company;

        \Stripe\Stripe::setApiKey($stripeSecretKey);
        header('Content-Type: application/json');

        $YOUR_DOMAIN = 'http://localhost:3333';

        $checkout_session = \Stripe\Checkout\Session::create([
        'line_items' => [[
            # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
            'price' => 'price_1PvoLELyDYmMLfv5QBSumsxt',
            'quantity' => 1,
        ]],
        'mode' => 'subscription',
        'success_url' => $YOUR_DOMAIN . '/' . $companyId . '/dashboard/billing?payment=success',
        'cancel_url' => $YOUR_DOMAIN . '/' . $companyId . '/dashboard/billing?payment=cancel',
        ]);

        // header("HTTP/1.1 303 See Other");
        // header("Location: " . $checkout_session->url);

        return response()->json([
            'data' => $checkout_session
        ]);

    }

    public function webhook(Request $request){

        try {

            $payload = json_decode($request->getContent(), true);
            $method = 'handle' . Str::studly(str_replace('.', '_', $payload['type']));

            \Illuminate\Support\Facades\Log::debug('ANTES DE LLEGAR AL METODO');
            \Illuminate\Support\Facades\Log::debug($method);

    
            if (method_exists($this, $method)) {
                $this->setMaxNetworkRetries();
    
                $response = $this->{$method}($payload);
    
                \Illuminate\Support\Facades\Log::debug($response);
    
                return $response;
            }
            
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

    }

    public function handlePaymentIntentCreated(Request $request){

        try {

            \Illuminate\Support\Facades\Log::debug('LLEGO AL METODO');

            return 'Hola';
            
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

    }
}
