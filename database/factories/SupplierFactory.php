<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $companyTypes = ['Ltd', 'Inc', 'Corp', 'LLC', 'Co', 'Group', 'Enterprises', 'International'];
        $companyType = $this->faker->randomElement($companyTypes);
        
        return [
            'Supplier_Name' => $this->faker->company() . ' ' . $companyType,
            'Supplier_Phone' => $this->faker->phoneNumber(),
            'Supplier_Address' => $this->faker->address(),
        ];
    }
}
