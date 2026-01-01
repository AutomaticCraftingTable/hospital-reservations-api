<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'doctor_id' => Doctor::factory(),
            'client_id' => Client::factory(),
            'title' => fake()->sentence(3),
            'room' => fake()->numberBetween(1, 50),
            'starting_at' => now(),
            'ending_at' => now()->addHour(),
        ];
    }
}
