<?php

namespace Database\Factories\Product;

use App\Models\Store\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product\Product>
 */
class ProductFactory extends Factory
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
            'image' => fake()->optional()->imageUrl(640, 480, 'apple', true),
            'price'=>$this->faker->randomFloat(2,1,1000),
            'amount'=>$this->faker->randomDigitNotZero(),
            'store_id'=>Store::factory(),
        ];
    }
}
