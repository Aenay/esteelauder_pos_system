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
        $categories = ['Skincare', 'Makeup', 'Fragrance', 'Hair Care', 'Body Care'];
        $brands = ['Estee Lauder', 'Clinique', 'MAC', 'Bobbi Brown', 'La Mer', 'Origins'];
        
        $price = $this->faker->randomFloat(2, 15, 500);
        $cost = $price * $this->faker->randomFloat(2, 0.3, 0.7); // Cost is 30-70% of price
        
        return [
            'Product_Name' => $this->faker->words(3, true),
            'Description' => $this->faker->sentence(),
            'Category' => $this->faker->randomElement($categories),
            'Brand' => $this->faker->randomElement($brands),
            'Price' => $price,
            'Cost' => $cost,
            'Quantity_on_Hand' => $this->faker->numberBetween(0, 200),
            'Reorder_Level' => $this->faker->numberBetween(10, 50),
            'SKU' => strtoupper($this->faker->bothify('SKU-####-????')),
            'Image_URL' => $this->faker->optional(0.8)->imageUrl(400, 400, 'cosmetics'),
            'Is_Active' => $this->faker->boolean(0.9),
        ];
    }
}
