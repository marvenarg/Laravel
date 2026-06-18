<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => fake()->words(3, true),
            'descripcion' => fake()->optional()->sentence(),
            'precio' => fake()->randomFloat(2, 1000, 500000),
            'stock' => fake()->numberBetween(1, 100),
        ];
    }
}
