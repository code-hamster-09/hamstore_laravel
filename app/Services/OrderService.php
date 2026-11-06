<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function create()
    {
        $user = Auth::user();

        return DB::transaction(function () use ($user) {
            $cartItems = $user->cartItems()->with('color')->get();
            $itemsId = $cartItems->pluck('product_id');

            if ($cartItems->isEmpty()) {
                throw ValidationException::withMessages(['error' => 'Your cart is empty']);
            }

            $products = Product::query()
                ->whereIn('id', $itemsId)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $totalSum = 0;
            $orderItemsData = [];

            foreach ($cartItems as $item) {
                $product = $products->get($item->product_id);

                if (! isset($product)) {
                    throw ValidationException::withMessages(['error' => 'This product does not exist']);
                }

                if ($item->quantity > $product->stock) {
                    throw ValidationException::withMessages(['error' => 'Not enough products']);
                }

                $product->decrement('stock', $item->quantity);
                $discountPercent = $product->discount_percent ?? 0;
                $costWithDiscount = intval(round($product->price * (100 - $discountPercent) / 100));
                $itemCost = $item->quantity * $costWithDiscount;
                $totalSum += $itemCost;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'color_id' => $item->color_id,
                    'product_name' => $product->name,
                    'price' => $costWithDiscount,
                    'quantity' => $item->quantity,
                    'hex' => $item->color->hex,
                ];
            }

            $order = $user->orders()->create(['total_sum' => $totalSum]);

            $order->items()->createMany($orderItemsData);

            $user->cartItems()->delete();

            return $order->load(['items']);
        });

    }
}
