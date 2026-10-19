<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Services\BrandService;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    //
    public function getAll()
    {
        return Brand::all();
    }

    public function show(Brand $brand)
    {
        return $brand;
    }

    public function store(Request $request, BrandService $service)
    {
        $data = $request->validate([
            'name' => ['string', 'between:1,255', 'required'],
        ]);

        return $service->create($data);
    }

    public function update(Request $request, BrandService $service)
    {
        $data = $request->validate([
            'newName' => ['string', 'between:1,255', 'required'],
            'prevSlug' => ['string', 'required', 'exists:brands,slug']
        ]);

        return $service->update($data);
    }

    public function destroy(Brand $brand)
    {
        return $brand->delete();
    }
}
