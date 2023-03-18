<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sellerID'=>Seller::all()->random()->id,
            'mediaID'=>Media::all()->random()->id,
            'description'=>$this->faker->text,
        ];
    }
}
