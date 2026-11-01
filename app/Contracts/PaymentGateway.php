<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface PaymentGateway
{
    public function createPayment(int $amount, int $orderId): array;

    public function verifyWebhook(Request $request): bool;
}
