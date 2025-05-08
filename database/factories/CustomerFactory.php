<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'tax_number' => fake()->boolean(30) ? fake()->numerify('###-###-###') : null,
            'is_active' => true,
        ];
    }

    public function inactive(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }

    public function company(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => fake()->company(),
                'tax_number' => fake()->numerify('###-###-###'),
            ];
        });
    }
}