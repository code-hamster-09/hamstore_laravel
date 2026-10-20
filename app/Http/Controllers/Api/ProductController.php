<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    //
    public function index(ProductService $service)
    {
        return $service->getAll();
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function store(StoreProductRequest $request, ProductService $service)
    {
        $user = $request->user();
        return $service->create($request->validated(), $user);
    }

    public function update(UpdateProductRequest $request, ProductService $service, Product $product)
    {
        return $service->update($request->validated(), $product);
    }

    public function destroy(Product $product)
    {
        return $product->delete();
    }
}
