<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class DoctorTest extends TestCase
{
    use RefreshDatabase;

    private function createDoctor(): Doctor
    {
        $user = User::factory()->create();
        return Doctor::factory()->create([
            'user_id' => $user->id,
            'specialization' => 'cardiologist',
        ]);
    }

    public function test_guest_can_view_all_doctors()
    {
        $this->createDoctor();
        $this->createDoctor();

        $res = $this->getJson('/api/doctors');

        $res->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_can_view_single_doctor()
    {
        $doctor = $this->createDoctor();

        $res = $this->getJson("/api/doctors/{$doctor->id}");

        $res->assertStatus(200)
            ->assertJsonFragment([
                'id' => $doctor->id,
                'specialization' => 'cardiologist',
            ]);
    }

    public function test_get_doctors_by_profession()
    {
        $this->createDoctor();

        Doctor::factory()->create([
            'user_id' => User::factory()->create()->id,
            'specialization' => 'dentist',
        ]);

        $res = $this->getJson('/api/doctors/profession/cardiologist');

        $res->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_authenticated_user_can_create_doctor()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $payload = [
            'user_id' => $user->id,
            'specialization' => 'surgeon',
        ];

        $res = $this->postJson('/api/doctors', $payload);

        $res->assertStatus(201);
        $this->assertDatabaseHas('doctors', $payload);
    }
}
