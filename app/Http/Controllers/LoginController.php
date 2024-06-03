<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $compte = Compte::where('login', $credentials['email'])->first();

        if (!$compte || !Hash::check($credentials['password'], $compte->password)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = $compte->user;
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }
}
