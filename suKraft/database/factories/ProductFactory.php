<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
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
            'name'=>"product_".$this->faker->name,
            'description'=>$this->faker->text,
            'price'=>mt_rand (0*10, 10*10) / 10,
            'salePrice'=>mt_rand (0*10, 10*10) / 10
        ];
    }
}
