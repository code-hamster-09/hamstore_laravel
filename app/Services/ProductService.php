<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{

    public function getAll()
    {
        return Product::paginate(20);
    }

    public function get(int $id)
    {
        return Product::find($id);
    }

    public function create($data)
    {
        return Product::create($data);
    }

    public function update(int $id, $data)
    {
        $product = Product::findOrFail($id);
        return $product->where('id', $id)->update($data);
    }

    public function delete(int $id)
    {
        $product = Product::findOrFail($id);
        return $product->where('id', $id)->delete();
    }
}
