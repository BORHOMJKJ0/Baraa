<?php

namespace Database\Factories\Category;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'image' => fake()->optional()->imageUrl(640, 480, 'technics', true),
        ];
    }
}
