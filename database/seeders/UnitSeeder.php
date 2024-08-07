<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            [
                'name' => 'Gram',
                'notation' => 'g'
            ],
            [
                'name' => 'Kilogram',
                'notation' => 'kg'
            ],
            [
                'name' => 'Milliliter',
                'notation' => 'ml'
            ],
            [
                'name' => 'Liter',
                'notation' => 'l'
            ],
            [
                'name' => 'Teaspoon',
                'notation' => 'tsp'
            ],
            [
                'name' => 'Tablespoon',
                'notation' => 'tbsp'
            ],
            [
                'name' => 'Cup',
                'notation' => 'cup'
            ],
            [
                'name' => 'Ounce',
                'notation' => 'oz'
            ],
            [
                'name' => 'Pound',
                'notation' => 'lb'
            ],
            [
                'name' => 'Piece',
                'notation' => 'pc'
            ],
            [
                'name' => 'Pinch',
                'notation' => 'pinch'
            ],
            [
                'name' => 'Dash',
                'notation' => 'dash'
            ]
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(['name' => $unit['name']], $unit);
        }
    }
}
