<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CustomersController extends Controller
{
    public function index()
    {
        return Customer::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|string|min:8', // <-- DIUBAH: dari max:50 menjadi min:8
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

        $customer = Customer::create([
            'customer_id' => Str::uuid(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        return response()->json($customer, 201);
    }

    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:50',
            'email' => 'sometimes|required|email|unique:customers,email,' . $id . ',customer_id',
            'password' => 'sometimes|nullable|string|min:8', // <-- DIUBAH: hapus max:50, tambah nullable
            'phone' => 'sometimes|required|string',
            'address' => 'sometimes|required|string',
        ]);

        if (isset($validated['password']) && $validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $customer->update($validated);
        return response()->json($customer);
    }
    
    public function destroy(string $id)
    {
        Customer::destroy($id);
        return response()->json(null, 204);
    }
}