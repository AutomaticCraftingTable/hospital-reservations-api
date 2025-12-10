<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with(['user', 'appointments'])->get();

        return response()->json($doctors);
    }

    public function show($id)
    {
        $doctor = Doctor::with(['user', 'appointments'])->find($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        return response()->json([
            'id' => $doctor->id,
            'user' => [
                'id' => $doctor->user->id,
                'email' => $doctor->user->email,
                'name' => $doctor->user->name,
                'surname' => $doctor->user->surname,
                'phone' => $doctor->user->phone,
            ],
            'description' => $doctor->description,
            'specialization' => $doctor->specialization,
            'appointments_count' => $doctor->appointments()->count(),
            'created_at' => $doctor->created_at->format('Y-m-d\TH:i:s.v\Z'),
        ]);
    }

    Public function profession($profession)
    {
        $doctors = Doctor::with(['user', 'appointments'])
            ->where('specialization', $profession)
            ->get();

        return response()->json($doctors);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'specialization' => 'nullable|string',
        ]);

        $doctor = Doctor::create($validated);

        return response()->json($doctor, 201);
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $validated = $request->validate([
            'description' => 'nullable|string',
            'specialization' => 'nullable|string',
        ]);

        $doctor->update($validated);

        return response()->json($doctor);
    }

    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);

        $doctor->delete();

        return response()->json(['success' => 'Doctor deleted']);
    }
}
