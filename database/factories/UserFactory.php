<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            "name" => fake()->name(),
            "surname" => fake()->lastName(),
            "email" => fake()->unique()->safeEmail(),
            "email_verified_at" => now(),
            "password" => Hash::make('password'),
            "isDoctor" => fake()->boolean(),
            "phone" => fake()->phoneNumber(),
            "remember_token" => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            "email_verified_at" => null,
        ]);
    }
}
