<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seller>
 */
class SellerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'userID'=>User::all()->random()->id,
            'name'=> $this->faker->name(),
            'description'=> $this->faker->text(),
            'mediaID'=> Media::all()->random()->id,
            'brandColors'=> $this->faker->hexColor(),
            'phoneNo'=> $this->faker->phoneNumber(),
        ];
    }
}
