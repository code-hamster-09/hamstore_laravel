<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $colors = [
            [
                'name' => 'No Color',
                'hex' => 'nocolor'
            ],
            [
                'name' => 'Millennial Pink',
                'hex' => '#F3CFC6'
            ],
            [
                'name' => 'Sage Green',
                'hex' => '#B2AC88'
            ],
            [
                'name' => 'Lavender Mist',
                'hex' => '#E6E6FA'
            ],
            [
                'name' => 'Peach Fuzz',
                'hex' => '#FFBE98'
            ],
            [
                'name' => 'Dusty Blue',
                'hex' => '#8C9EA3'
            ],
            [
                'name' => 'Emerald Green',
                'hex' => '#50C878'
            ],
            [
                'name' => 'Burgundy',
                'hex' => '#800020'
            ],
            [
                'name' => 'Midnight Blue',
                'hex' => '#191970'
            ],
            [
                'name' => 'Burnt Orange',
                'hex' => '#CC5500'
            ],
            [
                'name' => 'Plum',
                'hex' => '#8E4585'
            ],
            [
                'name' => 'Terracotta',
                'hex' => '#E2725B'
            ],
            [
                'name' => 'Mustard Gold',
                'hex' => '#E1AD01'
            ],
            [
                'name' => 'Olive Drab',
                'hex' => '#6B8E23'
            ],
            [
                'name' => 'Taupe',
                'hex' => '#483C32'
            ],
            [
                'name' => 'Sand Dollar',
                'hex' => '#EDE6D6'
            ],
            [
                'name' => 'Teal',
                'hex' => '#008080'
            ],
            [
                'name' => 'Coral Reef',
                'hex' => '#FF7F50'
            ],
            [
                'name' => 'Marigold',
                'hex' => '#EAA221'
            ],
            [
                'name' => 'Mint Cream',
                'hex' => '#F5FFFA'
            ],
            [
                'name' => 'Electric Indigo',
                'hex' => '#6F00FF'
            ],
        ];

        foreach ($colors as $color) {
            Color::firstOrCreate(['hex' => $color['hex']], $color);
        }
    }
}
