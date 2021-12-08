<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->name;

        return [
            'name' => $name,
            'slug' => Str::slug($name, '-'),
            'description' => $this->faker->text,
            'price' => $this->faker->randomFloat(2, 0, 100),
            'stock' => $this->faker->numberBetween(0, 100),
            'discount' => $this->faker->numberBetween(0, 100),
            'image' => $this->faker->imageUrl(),
            'category_id' => $this->faker->numberBetween(1, 15),
        ];
    }
}
