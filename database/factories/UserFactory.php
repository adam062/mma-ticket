<?php

namespace Database\Factories;

use App\Models\TicketType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUnpareoFT3FOSfllZOq5/MoJR.3VeWxKtVeqK4t7Jr5JgfMGO', // password
            'remember_token' => Str::random(10),
            'role' => 'admin',
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    public function gateMan(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'gate_man',
        ]);
    }
}
