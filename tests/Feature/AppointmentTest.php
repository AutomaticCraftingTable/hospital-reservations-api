<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    private function createAppointment(): Appointment
    {
        $doctorUser = User::factory()->create();
        $clientUser = User::factory()->create();

        $doctor = Doctor::factory()->create(['user_id' => $doctorUser->id]);
        $client = Client::factory()->create(['user_id' => $clientUser->id]);

        return Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'client_id' => $client->id,
            'title' => 'Visit',
            'room' => '101',
            'starting_at' => now(),
            'ending_at' => now()->addHour(),
        ]);
    }

    public function test_can_view_all_appointments()
    {
        $this->createAppointment();
        $this->createAppointment();

        $res = $this->getJson('/api/appointments');

        $res->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_can_view_single_appointment()
    {
        $appointment = $this->createAppointment();

        $res = $this->getJson("/api/appointments/{$appointment->id}");

        $res->assertStatus(200)
            ->assertJsonFragment([
                'id' => $appointment->id,
                'room' => '101',
            ]);
    }

    public function test_authenticated_user_can_create_appointment()
    {
        $doctorUser = User::factory()->create();
        $clientUser = User::factory()->create();

        $doctor = Doctor::factory()->create(['user_id' => $doctorUser->id]);
        $client = Client::factory()->create(['user_id' => $clientUser->id]);

        Sanctum::actingAs($doctorUser);

        $payload = [
            'doctor_id' => $doctor->id,
            'client_id' => $client->id,
            'title' => 'Consultation',
            'room' => '202',
            'starting_at' => now()->toISOString(),
            'ending_at' => now()->addHour()->toISOString(),
        ];

        $res = $this->postJson('/api/appointments', $payload);

        $res->assertStatus(201);
        $this->assertDatabaseHas('appointments', [
            'doctor_id' => $doctor->id,
            'client_id' => $client->id,
        ]);
    }
}
