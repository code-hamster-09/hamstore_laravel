<?php

use App\Http\Controllers\FakePayController;
use Illuminate\Support\Facades\Route;

Route::get('/fakepay/{paymentId}', [FakePayController::class, 'show']);
Route::post('/fakepay/{paymentId}', [FakePayController::class, 'confirm']);
