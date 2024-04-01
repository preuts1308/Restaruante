<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function inventory()
    {
        // Obtener los ingredientes y sus cantidades disponibles en la bodega de alimentos
        $ingredients = ingredients::all();
        return response()->json(['ingredients' => $ingredients]);
    }
    public function index()
    {
        //
        $orders = Order::all();
        return response()->json($orders);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {

        $request->validate([
            'recipe' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        Order::create([
            'recipe' => $request->recipe,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Order created successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
