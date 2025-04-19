<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Enregistrement d'un utilisateur
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'agent',
        ]);

        // Créer un token JWT
        $tokenResult = $user->createToken('Personal Access Token')->accessToken;

        return response()->json([
            'user' => $user,
            'access_token' => $tokenResult, // Ce sera maintenant un JWT
            'token_type' => 'Bearer',
        ], 201);
    }

    // Connexion d'un utilisateur
    public function login(Request $request)
    {
        // Validation des données reçues
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Vérification des identifiants
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants sont incorrects.'],
            ]);
        }

        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Créer un token JWT avec Passport
        $tokenResult = $user->createToken('Personal Access Token')->accessToken;

        // Retourner la réponse avec l'utilisateur et le token JWT
        return response()->json([
            'user' => $user,
            'access_token' => $tokenResult,  // Renvoi du token JWT
            'token_type' => 'Bearer',  // Le type du token
        ]);
    }

    // Déconnexion d'un utilisateur
    public function logout(Request $request)
    {
        // Révoquer le token actuel
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Déconnexion réussie']);
    }
}

