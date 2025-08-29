<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customerTypes = ['internal'];
        
        return [
            'Customer_Name' => $this->faker->name(),
            'Customer_Phone' => $this->faker->phoneNumber(),
            'Customer_Address' => $this->faker->address(),
            'Customer_Email' => $this->faker->unique()->safeEmail(),
            'Customer_Type' => $this->faker->randomElement($customerTypes),
            'Registration_Date' => $this->faker->dateTimeBetween('-2 years', 'now'),
        ];
    }
}
