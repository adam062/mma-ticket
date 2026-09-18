<?php

namespace Database\Factories;

use App\Models\TicketType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TicketTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name_en' => fake()->word(),
            'name_ar' => 'نوع التذكرة',
            'description_en' => fake()->sentence(),
            'description_ar' => 'وصف نوع التذكرة',
            'price' => fake()->numberBetween(100, 2000),
            'capacity' => fake()->numberBetween(50, 500),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }
}
