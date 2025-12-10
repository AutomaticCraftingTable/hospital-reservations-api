<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $doctors = Appointment::with(['doctor.user', 'client.user'])->get();

        return response()->json($doctors);
    }

    public function show($id)
    {
        $appointment = Appointment::with(['doctor.user', 'client.user'])->find($id);

        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        return response()->json([
            'id' => $appointment->id,
            'doctor' => $appointment->doctor ? [
                'id' => $appointment->doctor->id,
                'name' => $appointment->doctor->user->name,
                'surname' => $appointment->doctor->user->surname,
                'specialization' => $appointment->doctor->specialization,
            ] : null,
            'client' => $appointment->client ? [
                'id' => $appointment->client->id,
                'name' => $appointment->client->user->name,
                'surname' => $appointment->client->user->surname,
                'gender' => $appointment->client->gender,
            ] : null,
            'title' => $appointment->title,
            'room' => $appointment->room,
            'starting_at' => optional($appointment->starting_at)->format('Y-m-d\TH:i:s.v\Z'),
            'ending_at' => optional($appointment->ending_at)->format('Y-m-d\TH:i:s.v\Z'),
            'created_at' => $appointment->created_at->format('Y-m-d\TH:i:s.v\Z'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'client_id' => 'required|exists:clients,id',
            'title' => 'nullable|string',
            'room' => 'nullable|string',
            'starting_at' => 'required|date',
            'ending_at' => 'required|date|after:starting_at',
        ]);

        $appointment = Appointment::create($validated);

        return response()->json($appointment, 201);
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string',
            'room' => 'nullable|string',
            'starting_at' => 'nullable|date',
            'ending_at' => 'nullable|date|after:starting_at',
        ]);

        $appointment->update($validated);

        return response()->json($appointment);
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);

        $appointment->delete();

        return response()->json(['success' => 'Appointment deleted']);
    }
    public function getMyAppointments()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Not authenticated'], 401);
        }

        if ($user->isDoctor) {
            $doctor = $user->doctor;

            if (!$doctor) {
                return response()->json(['message' => 'Doctor profile not found'], 404);
            }

            $appointments = Appointment::with(['client.user'])
                ->where('doctor_id', $doctor->id)
                ->orderBy('starting_at')
                ->get();

            return response()->json([
                'role' => 'doctor',
                'doctor_id' => $doctor->id,
                'appointments' => $appointments,
            ]);
        }

        $client = $user->client;

        if (!$client) {
            return response()->json(['message' => 'Client profile not found'], 404);
        }

        $appointments = Appointment::with(['doctor.user'])
            ->where('client_id', $client->id)
            ->orderBy('starting_at')
            ->get();

        return response()->json([
            'role' => 'client',
            'client_id' => $client->id,
            'appointments' => $appointments,
        ]);
    }
}
