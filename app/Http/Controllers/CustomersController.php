<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Customer::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|string|max:50',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

        $customer = Customer::create(array_merge($validated, [
            'customer_id' => Str::uuid(),
        ]));

        return response()->json($customer, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:50',
            'email' => 'sometimes|required|email|unique:customers,email,' . $id . ',customer_id',
            'password' => 'sometimes|required|string|max:50',
            'phone' => 'sometimes|required|string',
            'address' => 'sometimes|required|string',
        ]);

        $customer->update($validated);
        return response()->json($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Customer::destroy($id);
        return response()->json(null, 204);
    }
}