<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;

class WarehouseController extends Controller
{
    public function processOrder(Request $request)
    {
        $ingredients = $request->input('ingredients');

        foreach ($ingredients as $ingredient) {
            $availableQuantity = $this->checkInventory($ingredient);

            if ($availableQuantity < $ingredient['quantity']) {
                $this->purchaseIngredients($ingredient);
            }
        }
        $this->deductIngredientsFromInventory($recipe);
        return response()->json(['message' => 'Tenemos los ingredientes']);
    }

    private function checkInventory($ingredient)
    {
 
        return Inventory::where('ingredient_id', $ingredient['id'])->value('quantity');
    }

    private function purchaseIngredients($ingredient)
    {
        $ingredientName = strtolower($ingredient['name']);
        // Hacer una solicitud HTTP a la plaza de mercado para comprar los ingredientes
        $response = Http::get('https://microservices-utadeo-arqfea471e6a9d4.herokuapp.com/api/v1/software-architecture/market-place', [
            'ingredient' => $ingredient['name']
        ]);
    
        $purchaseResponse = $response->json();
    
        if (isset($purchaseResponse['data'])) {
            $data = $purchaseResponse['data'];
            foreach ($data as $ingredientName => $quantityPurchased) {
                // Obtener el ID del ingrediente
                $ingredientId = $this->getIngredientIdByName($ingredientName);
                
                // Obtener la cantidad actual en inventario
                $currentQuantity = Inventory::where('ingredient_id', $ingredientId)->value('quantity');
                
                // Sumar la cantidad comprada a la cantidad actual
                $newQuantity = $currentQuantity + $quantityPurchased;
                
                // Actualizar el inventario con la nueva cantidad
                Inventory::updateOrCreate(
                    ['ingredient_id' => $ingredientId],
                    ['quantity' => $newQuantity]
                );
            }
        }
    }
    private function deductIngredientsFromInventory($recipe)
    {
        // Obtener los ingredientes requeridos para la receta
        $ingredients = $recipe->ingredients;

        // Reducir la cantidad de cada ingrediente en la bodega de alimentos
        foreach ($ingredients as $ingredient) {
            $inventory = Inventory::where('ingredient_id', $ingredient->id)->first();
            $inventory->quantity -= $ingredient->pivot->quantity;
            $inventory->save();
        }
    }
}  