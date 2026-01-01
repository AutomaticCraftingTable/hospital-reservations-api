<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerClient(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'gender' => 'nullable|string',
            'pesel' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);

        Client::create([
            'user_id' => $user->id,
            'gender' => $fields['gender'] ?? null,
            'pesel' => $fields['pesel'] ?? null,
        ]);

        $token = $user->createToken('client_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'role' => 'client',
        ], 201);
    }

    public function registerDoctor(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'specialization' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);

        Doctor::create([
            'user_id' => $user->id,
            'specialization' => $fields['specialization'] ?? null,
            'description' => $fields['description'] ?? null,
        ]);

        $token = $user->createToken('doctor_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'role' => 'doctor',
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                'message' => 'The provided credentials are incorrect.',
            ];
        }
        $token = $user->createToken($user->name);
        return [
            'user' => $user,
            'token' => $token->plainTextToken,
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });
        return [
            'message' => 'You are logged out.',
        ];
    }
}
