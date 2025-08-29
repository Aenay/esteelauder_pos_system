<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $products = [
            'Advanced Night Repair Serum',
            'Pure Color Creme Lipstick',
            'DayWear Moisturizer Multi-Protection Anti-Oxidant',
            'Nutritious Airy Lotion Duo Pores. Hydration. Glow.',
            'Re-Nutriv Ultimate Diamond Age Reversal Treatment Lotion Toner',
            'White Linen Legacy Eau de Parfum Spray',
            'Perfectly Clean Infusion Balancing Essence Treatment Lotion',
            'Nutritious Melting Soft Creme/Mask Moisturizer',
            'Advanced Night Repair Concentrated Recovery PowerFoil Mask',
            'Re-Nutriv Ultimate Diamond Transformative Brilliance Serum Refill',
            'Re-Nutriv Ultimate Facial Massager',
            'Futurist Hydra Rescue Moisturizing Foundation SPF 45',
            'Advanced Night Cleansing Gelée Cleanser with 15 Amino Acids',
            'Double Wear Infinite Waterproof Eyeliner',
            'Sumptuous Extreme Lash Multiplying Volume Mascara',
            'Pure Color Envy Sculpting Blush',
            'Double Wear Soft Glow Matte Cushion Makeup SPF 36 + Refill',
            'Beautiful Eau de Parfum Spray',
            'Bronze Goddess Powder Bronzer',
        ];

        // Pick one product at a time
        static $index = 0;
        $name = $products[$index % count($products)];
        $image = ($index + 1) . '.jpg'; // 1.jpg → 20.jpg
        $index++;

        $price = $this->faker->randomFloat(2, 15, 500);
        $cost = round($price * $this->faker->randomFloat(2, 0.3, 0.7), 2);

        return [
            'Product_Name' => $name,
            'Description' => $this->faker->sentence(),
            'Price' => $price,
            'Quantity_on_Hand' => $this->faker->numberBetween(0, 200),
            'SKU' => strtoupper(Str::substr(Str::slug($name, ''), 0, 3)) . '-' . Str::upper(Str::random(5)),
            'image' => 'products/' . $image, // stored inside storage/app/public/products/
        ];
    }
}
