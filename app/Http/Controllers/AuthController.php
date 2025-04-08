<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // A User modell importálása
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'string|max:255'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'user'
        ]);

        return response()->json([
            'message' => 'Regisztráció sikeres!',
            'user' => $user
        ], 201);
    }

    public function getUsers(Request $request)
    {
        $users = User::all();

        return response()->json($users);
    }

    public function login(Request $request)
{
    try {
        // Validate the request input
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|max:16',
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Helytelen felhasználónév vagy jelszó'], 401);
        }

        // Generate authentication token
        $token = $user->createToken($user->name);

        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['error' => 'Validation Error', 'messages' => $e->errors()], 422);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Server Error', 'message' => $e->getMessage()], 500);
    }
}

}