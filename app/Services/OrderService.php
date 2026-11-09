<?php

namespace App\Services;

use App\Contracts\PaymentGateway;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    protected PaymentGateway $payment;

    public function __construct(PaymentGateway $payment)
    {
        $this->payment = $payment;
    }

    public function create(User $user)
    {

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

    public function start(Order $order)
    {
        $payment = $this->payment->createPayment($order->total_sum, $order->id);
        $order->payments()->create([
            'provider_payment_id' => $payment['payment_id'],
            'amount' => $order->total_sum,
        ]);

        return $payment['redirect_url'];
    }
}
