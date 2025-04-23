<?php

namespace Database\Factories\Store;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'image' => fake()->optional()->imageUrl(640, 480, 'technics', true),
            'address' => $this->faker->address,
            'user_id' => User::factory(),
        ];
    }
}
