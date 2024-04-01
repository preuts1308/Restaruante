<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Order;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;

class RecipeController extends Controller
{
    public function receiveOrder()
    {
        $recipe = $this->selectRandomRecipe();

        // Guardar el pedido en la base de datos
        $order = new Order();
        $order->recipe_name = $recipe;
        $order->status = 'pendiente';
        $order->save();

        return response()->json(['message' => 'Pedido recibido y encolado correctamente', 'recipe' => $recipe]);
    }

    public function processOrders()
    {
        // Obtener todos los pedidos pendientes de la base de datos
        $pendingOrders = Order::where('status', 'pendiente')->get();

        foreach ($pendingOrders as $order) {
            $ingredients = $this->requestIngredients($order->recipe_name);
            if ($ingredients) {
                // Si hay suficientes ingredientes, cambiar el estado del pedido y proceder a prepararlo
                $order->status = 'en preparación';
                $order->save();
                // Aquí podrías iniciar la preparación del pedido
            }
        }
    }

    private function selectRandomRecipe()
    {
        // Obtener todas las recetas disponibles desde la base de datos
        $recipes = Recipe::pluck('name')->toArray();
        // Seleccionar aleatoriamente una receta
        $randomIndex = array_rand($recipes);
        return $recipes[$randomIndex];
    }

    private function requestIngredients($recipe)
    {
        // Hacer la solicitud a la bodega de alimentos
        $response = Http::post('gateway/api/warehouse/', ['recipe' => $recipe]);

        // Verificar si la solicitud fue exitosa y retornar los ingredientes si están disponibles
        if ($response->successful()) {
            return $response->json()['ingredients'];
        } else {
            return null;
        }
    }
}
