<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Missionary>
 */
class MissionaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'message' => fake()->paragraph(5),
            'image' => fake()->imageUrl(640, 480, 'people'),
            'disable_at' => fake()->optional()->dateTimeBetween('now', '+1 year'),
        ];
    }
} 