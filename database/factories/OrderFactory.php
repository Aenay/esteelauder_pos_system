<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Staff;
use App\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paymentMethods = ['card', 'cash', 'bank_transfer'];
        $paymentStatuses = ['pending', 'completed', 'failed'];
        
        $orderDate = $this->faker->dateTimeBetween('-6 months', 'now');
        $subtotal = $this->faker->randomFloat(2, 50, 1000);
        $discountAmount = $this->faker->optional(0.6)->randomFloat(2, 0, $subtotal * 0.2);
        $finalAmount = $subtotal - ($discountAmount ?? 0);
        
        return [
            'Order_Date' => $orderDate,
            'Staff_ID' => Staff::factory(),
            'Customer_ID' => Customer::factory(),
            'Promotion_ID' => $this->faker->optional(0.3)->randomElement([Promotion::factory()]),
            'Subtotal' => $subtotal,
            'Discount_Amount' => $discountAmount ?? 0,
            'Final_Amount' => $finalAmount,
            'payment_method' => $this->faker->randomElement($paymentMethods),
            'payment_status' => $this->faker->randomElement($paymentStatuses),
            'transaction_id' => $this->faker->optional(0.8)->bothify('TXN########'),
        ];
    }
}
