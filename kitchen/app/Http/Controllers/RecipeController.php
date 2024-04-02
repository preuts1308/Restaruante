<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Order;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::with('ingredients')->get();
        return response()->json(['recipes' => $recipes]);
    }

    public function processOrdersk()
    {
        // Obtener el primer pedido pendiente de la base de datos
        $pendingOrder = Order::where('status', 'pendiente')->first();

        // Verificar si hay un pedido pendiente
        if ($pendingOrder) {
            // Extraer la receta del pedido pendiente
            $recipe = Recipe::where('name', $pendingOrder->recipe_name)->first();

            // Verificar si se encontró la receta
            if ($recipe) {
                // Obtener la lista de ingredientes requeridos para la receta
                $ingredients = $recipe->ingredients;

                // Verificar si se encontraron los ingredientes
                if ($ingredients->isNotEmpty()) {
                    // Cambiar el estado del pedido a 'en preparación'
                    $pendingOrder->status = 'en preparación';
                    $pendingOrder->save();

                    // Crear un arreglo con los nombres y cantidades de los ingredientes
                    $ingredientList = [];
                    foreach ($ingredients as $ingredient) {
                        $ingredientList[] = [
                            'id' => $ingredient->id,
                            //'quantity' => $ingredient->pivot->quantity
                            'quantity' =>'1'
                        ];
                    }
                    //$jsonData = json_encode($ingredientList);
                    return response()->json(['ingredients' =>  $ingredientList]);
                    // Solicitar los ingredientes a la bodega de alimentos
                    $response = Http::post('gateway/api/warehouse/validate', ['ingredients' => $ingredientList]);
                    // $response = Http::withHeaders([
                    //     'Content-Type' => 'application/json',
                    // ])->post('gateway/api/warehouse/validate', ['data' => $jsonData]);
                    // Verificar si la solicitud fue exitosa
                    if ($response->successful()) {
                        // Retornar un mensaje indicando que se enviaron los ingredientes
                        $pendingOrder->status = 'listo';
                        $pendingOrder->save();
                        return response()->json(['message' => 'Se enviaron los ingredientes a la bodega de alimentos']);
                    } else {
                        // Retornar un mensaje de error si la solicitud falló
                        return response()->json(['message' => 'Error al enviar los ingredientes a la bodega de alimentos'], 500);
                    }
                } else {
                    // Retornar un mensaje de error si no se encontraron los ingredientes
                    return response()->json(['message' => 'No se encontraron los ingredientes requeridos para la receta'], 404);
                }
            } else {
                // Retornar un mensaje de error si no se encontró la receta
                return response()->json(['message' => 'No se encontró la receta asociada al pedido'], 404);
            }
        } else {
            // Retornar un mensaje de error si no hay pedidos pendientes
            return response()->json(['message' => 'No hay pedidos pendientes'], 404);
        }
    }



    private function requestIngredients($recipe)
    {
        // Hacer la solicitud a la bodega de alimentos
        $response = Http::post('gateway/api/warehouse/validate', ['recipe' => $recipe]);

        // Verificar si la solicitud fue exitosa y retornar los ingredientes si están disponibles
        if ($response->successful()) {
            return $response->json()['ingredients'];
        } else {
            return null;
        }
    }
}
