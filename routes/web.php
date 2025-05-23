<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StripePaymentController;

Route::post('/stripe/checkout', [StripePaymentController::class, 'stripeCheckout'])->name('checkout.credit-card');	
Route::get('/stripe/create-payment', [StripePaymentController::class, 'createPaymentIntent']);

