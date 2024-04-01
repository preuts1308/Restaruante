<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\Ingredient;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Receta 1: Pollo al Limón con Arroz
        $recipe1 = Recipe::create([
            'name' => 'Pollo al Limón con Arroz',
            'description' => 'Deliciosa receta de pollo al limón con arroz.',
        ]);

        $ingredients1 = [
            'Chicken',
            'Lemon',
            'Rice',
            'Onion',
            'Ketchup',
            'Lettuce',
            'Tomato',
            'Cheese',
            'Meat',
            'Potato',
        ];

        foreach ($ingredients1 as $ingredientName) {
            $ingredient = Ingredient::firstOrCreate(['name' => $ingredientName]);
            $recipe1->ingredients()->attach($ingredient->id);
        }

        // Receta 2: Ensalada de Pollo
        $recipe2 = Recipe::create([
            'name' => 'Ensalada de Pollo',
            'description' => 'Refrescante ensalada de pollo con vegetales.',
        ]);

        $ingredients2 = [
            'Chicken',
            'Lemon',
            'Lettuce',
            'Tomato',
            'Onion',
        ];

        foreach ($ingredients2 as $ingredientName) {
            $ingredient = Ingredient::firstOrCreate(['name' => $ingredientName]);
            $recipe2->ingredients()->attach($ingredient->id);
        }

        // Receta 3: Hamburguesa con Queso
        $recipe3 = Recipe::create([
            'name' => 'Hamburguesa con Queso',
            'description' => 'Clásica hamburguesa con queso, lechuga y tomate.',
        ]);

        $ingredients3 = [
            'Meat',
            'Cheese',
            'Lettuce',
            'Tomato',
            'Onion',
        ];

        foreach ($ingredients3 as $ingredientName) {
            $ingredient = Ingredient::firstOrCreate(['name' => $ingredientName]);
            $recipe3->ingredients()->attach($ingredient->id);
        }

        // Receta 4: Sopa de Patatas
        $recipe4 = Recipe::create([
            'name' => 'Sopa de Patatas',
            'description' => 'Sopa reconfortante de patatas con cebolla y tomate.',
        ]);

        $ingredients4 = [
            'Potato',
            'Onion',
            'Tomato',
        ];

        foreach ($ingredients4 as $ingredientName) {
            $ingredient = Ingredient::firstOrCreate(['name' => $ingredientName]);
            $recipe4->ingredients()->attach($ingredient->id);
        }

        // Receta 5: Arroz con Pollo
        $recipe5 = Recipe::create([
            'name' => 'Arroz con Pollo',
            'description' => 'Plato tradicional de arroz con pollo, cebolla y limón.',
        ]);

        $ingredients5 = [
            'Chicken',
            'Rice',
            'Onion',
            'Lemon',
        ];

        foreach ($ingredients5 as $ingredientName) {
            $ingredient = Ingredient::firstOrCreate(['name' => $ingredientName]);
            $recipe5->ingredients()->attach($ingredient->id);
        }

        // Receta 6: Tortilla de Patatas
        $recipe6 = Recipe::create([
            'name' => 'Tortilla de Patatas',
            'description' => 'Tortilla española clásica de patatas con cebolla.',
        ]);

        $ingredients6 = [
            'Potato',
            'Onion',
            'Lettuce',
        ];

        foreach ($ingredients6 as $ingredientName) {
            $ingredient = Ingredient::firstOrCreate(['name' => $ingredientName]);
            $recipe6->ingredients()->attach($ingredient->id);
        }
    }
}
