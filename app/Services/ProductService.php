<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{

    public function getAll()
    {
        return Product::with(['colors', 'brand_id', 'category_id'])->paginate(20);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(array $data)
    {
        $id = $data['id'];
        return Product::where('id', $id)->update($data);
    }
}
