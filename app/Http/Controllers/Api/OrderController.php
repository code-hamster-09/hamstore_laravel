<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrderService;

class OrderController extends Controller
{
    //
    public function store(OrderService $service)
    {
        $order = $service->create();

        return response()->json(['message' => 'Checkout created', 'data' => $order], 201);

    }
}
