<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CartController extends Controller
{
    //
    public function index(Request $request)
    {
        return $request->user()->cartItems()->with(['product'])->paginate(20);
    }

    public function store(Request $request)
    {
        $user = $request->user()->id;
        $cartItem = $request->validate([
            'product_id' => ['integer', 'required', Rule::exists('products', 'id')->whereNull('deleted_at')],
            'quantity' => ['integer', 'required', 'min:1'],
            'color_id' => ['integer', 'required',
                Rule::exists('color_product', 'color_id')
                    ->where('product_id', $request->input('product_id')),
            ],
        ]);

        return CartItem::firstOrCreate([
            'user_id' => $user,
            'product_id' => $cartItem['product_id'],
            'color_id' => $cartItem['color_id'],
        ], ['quantity' => $cartItem['quantity']]);
    }

    public function update(CartItem $cartItem, Request $request)
    {
        abort_if($request->user()->id != $cartItem->user_id, 403);

        $newQuantity = $request->validate([
            'quantity' => ['integer', 'required', 'between:1,100'],
        ]);

        $cartItem->update([
            'quantity' => $newQuantity['quantity'],
        ]);

        return response()->json(['success' => true, 'cartItem' => $cartItem]);
    }

    public function destroy(CartItem $cartItem, Request $request)
    {
        abort_if($request->user()->id != $cartItem->user_id, 403);
        $cartItem->delete();

        return response()->noContent();
    }
}
