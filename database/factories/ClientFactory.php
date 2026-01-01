<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state([
                'isDoctor' => false,
            ]),
            'gender' => fake()->randomElement(['male', 'female']),
            'pesel' => fake()->numerify('###########'),
        ];
    }
}
