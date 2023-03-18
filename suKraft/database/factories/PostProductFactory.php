<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PostProduct>
 */
class PostProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'postID'=>Post::all()->random()->id,
            'productID'=>Product::all()->random()->id,
        ];
    }
}
