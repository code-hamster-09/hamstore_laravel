<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    //
    public function store(OrderService $service)
    {
        $user = Auth::user();
        $order = $service->create($user);

        return response()->json(['message' => 'Checkout created', 'data' => $order], 201);

    }

    public function pay(Order $order, OrderService $service)
    {
        $user = Auth::user();
        abort_if($order->user_id !== $user->id, 403);

        if ($order->status !== 'pending') {
            throw ValidationException::withMessages(['error' => 'Order is already paid or failed']);
        }
        $redirectUrl = $service->start($order);

        return response()->json(['redirect_url' => $redirectUrl], 201);
    }
}
