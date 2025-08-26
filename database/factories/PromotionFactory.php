<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promotion>
 */
class PromotionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $discountTypes = ['Percentage', 'Fixed Amount'];
        
        $startDate = $this->faker->dateTimeBetween('-1 month', '+2 months');
        $endDate = $this->faker->dateTimeBetween($startDate, '+3 months');
        
        $discountType = $this->faker->randomElement($discountTypes);
        $discountValue = 0;
        
        if ($discountType === 'Percentage') {
            $discountValue = $this->faker->numberBetween(5, 50);
        } else {
            $discountValue = $this->faker->randomFloat(2, 5, 100);
        }
        
        return [
            'Promotion_Name' => $this->faker->words(3, true) . ' Sale',
            'Description' => $this->faker->sentence(),
            'Discount_Type' => $discountType,
            'Discount_Value' => $discountValue,
            'Start_Date' => $startDate,
            'End_Date' => $endDate,
            'Is_Active' => $this->faker->boolean(0.8),
        ];
    }
}
