<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $ingredients = [
            'Tomato',
            'Lemon',
            'Potato',
            'Rice',
            'Ketchup',
            'Lettuce',
            'Onion',
            'Cheese',
            'Meat',
            'Chicken',
        ];

        foreach($ingredients as $ingredient){
            Ingredient::create([
                'name' => $ingredient
            ]);
        }
    }
}
