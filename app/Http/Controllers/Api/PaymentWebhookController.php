<?php

namespace App\Http\Controllers\Api;

use App\Contracts\PaymentGateway;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentWebhookController extends Controller
{
    protected PaymentGateway $payment;

    public function __construct(PaymentGateway $payment)
    {
        $this->payment = $payment;
    }

    public function handle(Request $request)
    {
        abort_if(! $this->payment->verifyWebhook($request), 401);

        return DB::transaction(function () use ($request) {
            $id = $request->payment_id;
            $payment = Payment::query()->where('provider_payment_id', $id)->lockForUpdate()->firstOrFail();

            if ($payment->status === 'paid') {
                return response()->json(['status' => 'success'], 200);
            }

            $payment->update(['status' => 'paid']);
            $payment->order()->update(['status' => 'paid']);

            return response()->json(['status' => 'success'], 200);
        });
    }
}
