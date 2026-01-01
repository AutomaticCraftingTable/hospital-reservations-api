<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state([
                'isDoctor' => true,
            ]),
            'description' => fake()->sentence(),
            'specialization' => fake()->randomElement([
                'cardiology',
                'neurology',
                'orthopedics',
                'dermatology',
            ]),
        ];
    }
}
