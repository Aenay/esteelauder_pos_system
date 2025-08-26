<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Staff>
 */
class StaffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Staff_Name' => $this->faker->name(),
            'Staff_Phone' => $this->faker->phoneNumber(),
            'Staff_Address' => $this->faker->address(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'department_id' => Department::factory(),
        ];
    }
}
