<?php

namespace Database\Factories;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'address'=>$this->faker->address(),
            'email'=>$this->faker->email(),
            'firstName'=>$this->faker->firstName(),
            'lastName'=>$this->faker->lastName(),
            'phone'=>$this->faker->phoneNumber(),
            'userID'=>User::all()->random()->id,
            'sellerID'=>Seller::all()->random()->id,
            'description'=>$this->faker->text(),
        ];
    }
}
