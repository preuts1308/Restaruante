<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WarehouseController extends Controller
{
    public function processOrder(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['ingredients']) || !is_array($data['ingredients'])) {
            return response()->json(['message' => 'La estructura del JSON es incorrecta'], 400);
        }

        foreach ($data['ingredients'] as $ingredient) {
            // Verificar la cantidad disponible en el inventario para cada ingrediente
            $availableQuantity = $this->checkInventory($ingredient['id']);

            // Verificar si la cantidad disponible es insuficiente
            if ($availableQuantity < $ingredient['quantity']) {
                return response()->json(['message' => 'No hay suficiente cantidad del ingrediente ' . $ingredient['id']], 400);
            }
        }

        // Descontar los ingredientes utilizados del inventario
        $this->deductIngredientsFromInventory($data['ingredients']);

        // Retornar un mensaje indicando que se han obtenido los ingredientes necesarios
        return response()->json(['message' => 'Tenemos los ingredientes'], 200);
    }

    private function checkInventory($ingredientId)
    {
        return Inventory::where('ingredient_id', $ingredientId)->value('quantity');
    }

    private function purchaseIngredients()
    {
        // Obtener los ingredientes con cantidad igual a 0
        $ingredientsToUpdate = Inventory::where('quantity', 0)->get();

        foreach ($ingredientsToUpdate as $ingredient) {
            // Convertir el nombre del ingrediente a minúsculas
            $ingredientName = strtolower($ingredient->ingredient_name);

            // Enviar una solicitud HTTP a la API de la plaza de mercado
            $response = Http::get('https://microservices-utadeo-arq-fea471e6a9d4.herokuapp.com/api/v1/software-architecture/market-place', [
                'ingredient' => $ingredientName
            ]);

            $purchaseResponse = $response->json();

            if (isset($purchaseResponse[$ingredientName]) && $purchaseResponse[$ingredientName] > 0) {
                // La compra fue exitosa
                // Actualizar el inventario con la cantidad comprada
                Inventory::updateOrCreate(
                    ['ingredient_name' => $ingredientName],
                    ['quantity' => $purchaseResponse[$ingredientName]]
                );
            } else {
                // La compra no fue exitosa o el ingrediente no está disponible
                // Mostrar un mensaje de error al usuario o realizar alguna acción correspondiente
                // Por ejemplo:
                // return response()->json(['message' => 'Error al comprar ingredientes o ingrediente no disponible'], 400);
            }
        }
    }
    private function deductIngredientsFromInventory($ingredients)
    {
        foreach ($ingredients as $ingredient) {
            // Obtener la cantidad actual en inventario
            $currentQuantity = Inventory::where('ingredient_id', $ingredient['id'])->first();

            // Si no se encuentra el ingrediente en el inventario, se crea con la cantidad indicada
            if (!$currentQuantity) {
                purchaseIngredients();
            } else {
                // Restar la cantidad utilizada de la cantidad actual
                $newQuantity = $currentQuantity->quantity - $ingredient['quantity'];

                // Si la cantidad final es menor a cero, se lanza una excepción
                if ($newQuantity < 0) {
                    throw new InsufficientQuantityException('La cantidad del ingrediente ' . $ingredient['id'] . ' es insuficiente');
                }

                // Actualizar el inventario con la nueva cantidad
                $currentQuantity->update([
                    'quantity' => $newQuantity
                ]);
            }
        }
    }
}
