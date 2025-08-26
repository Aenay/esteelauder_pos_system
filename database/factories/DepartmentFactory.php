<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departments = [
            'Sales',
            'Customer Service',
            'Inventory Management',
            'Marketing',
            'Human Resources',
            'Finance',
            'Operations',
            'Quality Assurance'
        ];
        
        return [
            'Department_Name' => $this->faker->randomElement($departments),
            'Description' => $this->faker->sentence(),
        ];
    }
}
