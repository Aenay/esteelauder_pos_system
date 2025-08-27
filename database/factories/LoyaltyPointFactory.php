<?php

namespace Database\Factories;

use App\Models\LoyaltyPoint;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoyaltyPoint>
 */
class LoyaltyPointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tierLevels = ['bronze', 'silver', 'gold', 'platinum'];
        $tierLevel = $this->faker->randomElement($tierLevels);
        
        // Generate points based on tier level
        $pointsEarned = match($tierLevel) {
            'bronze' => $this->faker->numberBetween(0, 99),
            'silver' => $this->faker->numberBetween(100, 499),
            'gold' => $this->faker->numberBetween(500, 999),
            'platinum' => $this->faker->numberBetween(1000, 2000),
        };
        
        $pointsUsed = $this->faker->numberBetween(0, $pointsEarned * 0.3); // Use up to 30% of earned points
        
        return [
            'Customer_ID' => Customer::factory(),
            'points_earned' => $pointsEarned,
            'points_used' => $pointsUsed,
            'current_balance' => $pointsEarned - $pointsUsed,
            'tier_level' => $tierLevel,
            'last_activity_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'notes' => $this->faker->optional(0.7)->sentence(),
        ];
    }

    /**
     * Indicate that the loyalty record is for a bronze tier customer.
     */
    public function bronze(): static
    {
        return $this->state(fn (array $attributes) => [
            'tier_level' => 'bronze',
            'points_earned' => $this->faker->numberBetween(0, 99),
        ]);
    }

    /**
     * Indicate that the loyalty record is for a silver tier customer.
     */
    public function silver(): static
    {
        return $this->state(fn (array $attributes) => [
            'tier_level' => 'silver',
            'points_earned' => $this->faker->numberBetween(100, 499),
        ]);
    }

    /**
     * Indicate that the loyalty record is for a gold tier customer.
     */
    public function gold(): static
    {
        return $this->state(fn (array $attributes) => [
            'tier_level' => 'gold',
            'points_earned' => $this->faker->numberBetween(500, 999),
        ]);
    }

    /**
     * Indicate that the loyalty record is for a platinum tier customer.
     */
    public function platinum(): static
    {
        return $this->state(fn (array $attributes) => [
            'tier_level' => 'platinum',
            'points_earned' => $this->faker->numberBetween(1000, 2000),
        ]);
    }
}

