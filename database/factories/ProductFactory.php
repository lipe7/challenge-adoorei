<?php

namespace Database\Factories;

use App\Domain\Product\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Sale\Sale>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(2),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'description' => $this->faker->paragraph,
        ];
    }
}
