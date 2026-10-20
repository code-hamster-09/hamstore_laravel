<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProductService
{

    public function getAll()
    {
        return Product::with(['colors', 'brand', 'category'])->paginate(20);
    }

    public function create(array $data, User $user)
    {
        return DB::transaction(function () use ($data, $user) {
            $product = $user->products()->create($data);
            if ($data['color_ids']) {
                $ids = $data['color_ids'];
                $product->colors()->sync($ids);
            }
            return $product->fresh()->with(['colors', 'brand', 'category']);
        });
    }

    public function update(array $data, Product $product)
    {
        return DB::transaction(function () use ($data, $product) {
            $product->update($data);
            $ids = $data['color_ids'];
            $product->colors()->sync($ids);
            return $product->fresh()->with(['colors', 'brand', 'category']);
        });
    }
}
