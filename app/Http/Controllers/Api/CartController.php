<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //
    public function getAll(Request $request)
    {
        return $request->user()->cartItems()->paginate(20);
    }

    public function store(Request $request)
    {
        $user = $request->user()->id;
        $cartItem = $request->validate([
            'product_id' => ['integer', 'required', 'exists:products,id'],
            'quantity' => ['integer', 'required', 'min:1']
        ]);

        return CartItem::firstOrCreate([
            'user_id' => $user,
            'product_id' => $cartItem['product_id']
        ], ['quantity' => $cartItem['quantity']]);
    }

    public function destroy(Request $request)
    {
        return $request->delete();
    }
}
