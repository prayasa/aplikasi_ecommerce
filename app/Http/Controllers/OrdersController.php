<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Order::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|string|exists:customers,customer_id',
            'order_date' => 'required|date',
            'total_amount' => 'required|integer',
            'status' => 'required|string',
        ]);

        $order = Order::create(array_merge($validated, [
            'order_id' => Str::uuid(),
        ]));

        return response()->json($order, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::findOrFail($id);
        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'customer_id' => 'sometimes|required|string|exists:customers,customer_id',
            'order_date' => 'sometimes|required|date',
            'total_amount' => 'sometimes|required|integer',
            'status' => 'sometimes|required|string',
        ]);

        $order->update($validated);

        return response()->json($order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Order::destroy($id);
        return response()->json(null, 204);
    }
}
