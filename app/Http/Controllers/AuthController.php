<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ]);

        if (Customer::where('email', $credentials['email'])->exists()) {
            return response()->json(['message' => 'Akun ini adalah akun Pembeli. Silakan login di form Pembeli.'], 422);
        }

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Kredensial tidak valid'], 401);
        }

        $user = $request->user();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}