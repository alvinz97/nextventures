<?php

namespace Domain\Product\Factories;

use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;
    public function definition(): array
    {
        $title = $this->faker->unique()->words(3, true);

        return [
            'title' => ucfirst($title),
            'slug' => Str::slug($title) . '-' . Str::random(5),
            'stock' => $this->faker->numberBetween(0, 200),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
