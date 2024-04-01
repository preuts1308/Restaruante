<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RecipeController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
// Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');
// // routes/api.php
// Route::get('/management', function () {
//     return response()->json(['message' => 'Hola Mundo desde ms-manage'], 200);
// });

Route::post('/generar-pedido', [RecipeController::class, 'receiveOrder'])->name('pedidos.store');
#Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
Route::get('/orders', [OrderController::class, 'index']);
Route::get('/inventory', [OrderController::class, 'inventory']);
