<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Models\Brand;

class BrandService
{
    public function create($data)
    {
        $slug = Str::slug($data['name']);
        return Brand::create([$slug, $data['name']]);
    }

    public function update($data)
    {
        $prevSlug = $data['prevSlug'];
        $newSlug = Str::slug($data['newName']);
        $newData = [
            'slug' => $newSlug,
            'name' => $data['newName']
        ];

        return Brand::where('slug', $prevSlug)->update($newData);
    }
}
