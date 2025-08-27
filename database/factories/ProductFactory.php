<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
        $price = $this->faker->randomFloat(2, 15, 500);
        
        return [
            'Product_Name' => $this->faker->words(3, true),
            'SKU' => strtoupper($this->faker->bothify('SKU-####-????')),
            'Price' => $price,
            'Quantity_on_Hand' => $this->faker->numberBetween(0, 200),
            'description' => $this->faker->sentence(),
            'image' => $this->faker->optional(0.8)->imageUrl(400, 400, 'cosmetics'),
        ];
    }
}
