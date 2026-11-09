<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FakePayController extends Controller
{
    public function show($paymentId)
    {
        $payment = Payment::query()->where('provider_payment_id', $paymentId)->firstOrFail();

        return view('fakepay', ['payment' => $payment]);
    }

    public function confirm(Request $request, $paymentId)
    {
        $payment = Payment::query()->where('provider_payment_id', $paymentId)->firstOrFail();

        $jsonBody = json_encode([
            'payment_id' => $paymentId,
            'status' => $request->input('status'),
            'amount' => $payment->amount,
        ]);

        $signature = hash_hmac('sha256', $jsonBody, config('services.fakepay.secret'));
        $url = config('services.fakepay.url');

        Http::withHeader('X-Signature', $signature)
            ->withBody($jsonBody, 'application/json')
            ->post($url);

        return back();
    }
}
