<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'productID'=>Product::all()->random()->id,
            'userID'=>User::all()->random()->id,
            'reviewText'=>$this->faker->text,
            'rating'=>$this->faker->randomElement([1,3,5]),
        ];
    }
}
