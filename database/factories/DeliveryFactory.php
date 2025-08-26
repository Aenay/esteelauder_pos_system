<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Delivery>
 */
class DeliveryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['pending', 'in_transit', 'delivered', 'cancelled'];
        $carriers = ['FedEx', 'UPS', 'DHL', 'USPS', 'Amazon Logistics', 'Local Delivery'];
        
        $expectedDate = $this->faker->dateTimeBetween('now', '+2 months');
        $actualDate = null;
        $status = $this->faker->randomElement($statuses);
        
        if ($status === 'delivered') {
            $actualDate = $this->faker->dateTimeBetween('-1 month', $expectedDate);
        }
        
        return [
            'Supplier_ID' => Supplier::factory(),
            'Delivery_Reference' => 'DEL-' . strtoupper($this->faker->bothify('??????')),
            'Expected_Delivery_Date' => $expectedDate,
            'Actual_Delivery_Date' => $actualDate,
            'Status' => $status,
            'Notes' => $this->faker->optional(0.6)->sentence(),
            'Total_Amount' => $this->faker->randomFloat(2, 100, 5000),
            'Tracking_Number' => $this->faker->optional(0.8)->bothify('TRK########'),
            'Carrier' => $this->faker->randomElement($carriers),
        ];
    }
}
