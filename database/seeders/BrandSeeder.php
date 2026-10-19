<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'TechSound',
            'TimeStyle',
            'EcoWear',
            'FitTech',
            'UrbanStyle',
            'ChargeTech'
        ];

        foreach ($brands as $name) {
            $slug = Str::slug($name);
            $data = [
                'slug' => $slug,
                'name' => $name
            ];

            Brand::firstOrCreate(['slug' => $slug], $data);
        }
    }
}
