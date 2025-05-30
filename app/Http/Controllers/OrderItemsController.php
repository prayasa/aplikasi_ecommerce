<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return OrderItem::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|string|exists:orders,order_id',
            'product_id' => 'required|string|exists:products,product_id',
            'quantity' => 'required|integer',
            'price' => 'required|integer',
        ]);

        $orderItem = OrderItem::create(array_merge($validated, [
            'id' => Str::uuid(),
        ]));

        return response()->json($orderItem, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $orderItem = OrderItem::findOrFail($id);
        return response()->json($orderItem);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $orderItem = OrderItem::findOrFail($id);

        $validated = $request->validate([
            'order_id' => 'sometimes|required|string|exists:orders,order_id',
            'product_id' => 'sometimes|required|string|exists:products,product_id',
            'quantity' => 'sometimes|required|integer',
            'price' => 'sometimes|required|integer',
        ]);

        $orderItem->update($validated);

        return response()->json($orderItem);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        OrderItem::destroy($id);
        return response()->json(null, 204);
    }
}
