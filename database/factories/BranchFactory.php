<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = [
            'Kuala Lumpur' => 'Selangor',
            'Penang' => 'Penang',
            'Johor Bahru' => 'Johor',
            'Ipoh' => 'Perak',
            'Kuching' => 'Sarawak',
            'Kota Kinabalu' => 'Sabah',
            'Alor Setar' => 'Kedah',
            'Melaka' => 'Melaka',
            'Kuantan' => 'Pahang',
            'Shah Alam' => 'Selangor'
        ];

        $city = $this->faker->randomElement(array_keys($cities));
        $state = $cities[$city];

        $statuses = ['active', 'active', 'active', 'inactive', 'maintenance'];
        
        return [
            'branch_code' => 'BR' . strtoupper($this->faker->bothify('??##')),
            'branch_name' => $city . ' ' . $this->faker->randomElement(['Central', 'Mall', 'Plaza', 'Branch', 'Store', 'Outlet']),
            'address' => $this->faker->streetAddress(),
            'city' => $city,
            'state' => $state,
            'postal_code' => $this->faker->numberBetween(10000, 99999),
            'country' => 'Malaysia',
            'phone' => '+60 ' . $this->faker->numberBetween(1, 9) . ' ' . $this->faker->numberBetween(1000, 9999) . ' ' . $this->faker->numberBetween(1000, 9999),
            'email' => strtolower(str_replace(' ', '', $city)) . '@esteelauder.com',
            'manager_name' => $this->faker->name(),
            'manager_phone' => '+60 ' . $this->faker->numberBetween(10, 19) . ' ' . $this->faker->numberBetween(100, 999) . ' ' . $this->faker->numberBetween(1000, 9999),
            'manager_email' => $this->faker->email(),
            'opening_hours' => [
                'monday' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
                'tuesday' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
                'wednesday' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
                'thursday' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
                'friday' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
                'saturday' => ['open' => '10:00', 'close' => '16:00', 'closed' => false],
                'sunday' => ['closed' => true]
            ],
            'status' => $this->faker->randomElement($statuses),
            'notes' => $this->faker->optional(0.7)->sentence(),
        ];
    }

    /**
     * Indicate that the branch is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the branch is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Indicate that the branch is under maintenance.
     */
    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'maintenance',
        ]);
    }

    /**
     * Indicate that the branch is closed.
     */
    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed',
        ]);
    }
}
