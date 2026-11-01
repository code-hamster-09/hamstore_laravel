<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Override;

class FakePaymentGateway implements PaymentGateway
{
    #[Override]
    public function createPayment(int $amount, int $orderId): array
    {
        $uuid = Str::uuid7()->toString();

        return [
            'payment_id' => $uuid,
            'redirect_url' => 'http://localhost:8080/fake-pay/'.$uuid,
        ];
    }

    #[Override]
    public function verifyWebhook(Request $request): bool
    {
        $providerSignature = $request->header('X-Signature', '');
        $reqBody = $request->getContent();
        $mySignature = hash_hmac('sha256', $reqBody, config('services.fakepay.secret'));

        return hash_equals($mySignature, $providerSignature);
    }
}
