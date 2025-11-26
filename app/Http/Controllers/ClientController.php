<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function show($id)
    {
        $client = Client::with(['user', 'appointments'])->find($id);

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        return response()->json([
            'id' => $client->id,
            'user' => [
                'id' => $client->user->id,
                'email' => $client->user->email,
                'name' => $client->user->name,
                'surname' => $client->user->surname,
                'phone' => $client->user->phone,
            ],
            'gender' => $client->gender,
            'pesel' => $client->pesel,
            'appointments_count' => $client->appointments()->count(),
            'created_at' => $client->created_at->format('Y-m-d\TH:i:s.v\Z'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'gender' => 'nullable|string',
            'pesel' => 'nullable|string',
        ]);

        $client = Client::create($validated);

        return response()->json($client, 201);
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'gender' => 'nullable|string',
            'pesel' => 'nullable|string',
        ]);

        $client->update($validated);

        return response()->json($client);
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);

        $client->delete();

        return response()->json(['success' => 'Client deleted']);
    }
}
