<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    private function createClient(): Client
    {
        $user = User::factory()->create();
        return Client::factory()->create();
    }

    public function test_can_view_all_clients()
    {
        $this->createClient();
        $this->createClient();

        $res = $this->getJson('/api/clients');

        $res->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_view_single_client()
    {
        $client = $this->createClient();

        $res = $this->getJson("/api/clients/{$client->id}");

        $res->assertStatus(200)
            ->assertJsonFragment([
                'id' => $client->id,
            ]);
    }

    public function test_authenticated_user_can_create_client()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payload = [
            'user_id' => $user->id,
            'gender' => 'female',
            'pesel' => '98765432109',
        ];

        $res = $this->postJson('/api/clients', $payload);

        $res->assertStatus(201);
        $this->assertDatabaseHas('clients', $payload);
    }
}
