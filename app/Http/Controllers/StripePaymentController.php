<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StripePaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        $response = Http::withBasicAuth(env('STRIPE_SECRET_KEY'), '')	
            ->asForm()
            ->post('https://api.stripe.com/v1/payment_intents', [
                'amount' => 10000,
                'currency' => 'inr',
                'automatic_payment_methods[enabled]' => 'true',
            ]);
            
        $clientSecret = $response->json()['client_secret'];
        $publishableKey = env('STRIPE_PUBLIC_KEY');
        return view('stripe.checkout', [
            'clientSecret' => $clientSecret,
            'publishableKey' => $publishableKey
        ]);
    }

    public function stripeCheckout()
    {   
        dd('payment succesfully processed');
    }
}
