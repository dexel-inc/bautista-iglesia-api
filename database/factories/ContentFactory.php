<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content>
 */
class ContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['blog', 'video', 'podcast', 'article', 'news']),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(3),
            'image' => fake()->imageUrl(640, 480, 'abstract'),
        ];
    }
} 