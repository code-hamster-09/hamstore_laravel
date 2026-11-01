<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Electronics',
            'Accessories',
            'Clothing',
            'Furniture',
            'Toys',
            'Sports',
            'Pet Supplies',
            'Books & Stationery',
        ];

        foreach ($categories as $category) {
            $slug = Str::slug($category);
            $data = [
                'name' => $category,
                'slug' => $slug
            ];

            Category::firstOrCreate(['slug' => $slug], $data);
        }
    }
}
