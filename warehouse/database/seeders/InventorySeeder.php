<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Obtener todos los ingredientes
         $ingredients = Ingredient::all();

         // Precargar 5 unidades de cada ingrediente en el inventario
         foreach ($ingredients as $ingredient) {
             Inventory::create([
                 'ingredient_id' => $ingredient->id,
                 'quantity' => 5, // Cantidad inicial de 5 unidades
               //  'unit_price' => 0.0, 
             ]);
         }
     
    }
}
