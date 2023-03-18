<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'srcUrl'=>$this->faker->url(),
            'name'=>$this->faker->image(),
            'type'=>$this->faker->randomElement(['image','video']),
        ];
    }
}
