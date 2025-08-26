<?php

namespace Database\Factories;

use App\Models\Delivery;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeliveryDetail>
 */
class DeliveryDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantityOrdered = $this->faker->numberBetween(10, 200);
        $unitCost = $this->faker->randomFloat(2, 5, 100);
        $totalCost = $quantityOrdered * $unitCost;
        
        // For delivered deliveries, set received quantity
        $quantityReceived = 0;
        if ($this->faker->boolean(0.7)) { // 70% chance of being received
            $quantityReceived = $this->faker->numberBetween(0, $quantityOrdered);
        }
        
        return [
            'Delivery_ID' => Delivery::factory(),
            'Product_ID' => Product::factory(),
            'Quantity_Ordered' => $quantityOrdered,
            'Quantity_Received' => $quantityReceived,
            'Unit_Cost' => $unitCost,
            'Total_Cost' => $totalCost,
            'Notes' => $this->faker->optional(0.4)->sentence(),
        ];
    }
}
