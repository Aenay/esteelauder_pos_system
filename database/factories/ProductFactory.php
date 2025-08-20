<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $categories = ['Lipstick', 'Foundation', 'Perfume', 'Moisturizer', 'Eyeliner', 'Mascara', 'Blush'];

        $product = $this->faker->randomElement($categories);
        $shade = $this->faker->colorName();
        $sku = strtoupper(substr($product, 0, 3)) . '-' . Str::upper(Str::random(5));

        return [
            'Product_Name'       => $product . ' - ' . $shade,
            'SKU'                => $sku,
            'Price'              => $this->faker->randomFloat(2, 5, 200),
            'Quantity_on_Hand'   => $this->faker->numberBetween(10, 100),
            'description'        => $this->faker->sentence(10),
            'image'              => 'products/clear.gif', // relative to public/
        ];
    }
}
