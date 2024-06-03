<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Compte;
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class InscriptionController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'pays' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'email' => $request->email,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'pays' => $request->pays,
            'photo_de_profil' => $request->photo_de_profil,
        ]);


        $generatedPassword = Str::random(10);

        Compte::create([
            'login' => $user->email,
            'password' => Hash::make($generatedPassword),
            'user_id' => $user->id,
        ]);


        $token = $user->createToken('authToken')->plainTextToken;
        
        return response()->json(['token' => $token, 'password' => $generatedPassword], 201);
        $user->notify(new WelcomeNotification($generatedPassword));
    }

}
