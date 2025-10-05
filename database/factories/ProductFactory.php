<?php

namespace Database\Factories;

// use App\Helpers\Facades\Util;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
    public function definition()
    {
        return [
            'code' => $this->faker->numerify('#######'),
            'name' => fake()->name(),
            'name_kana' => fake()->name(),
            'unit_id' => 1,
            'price' => fake()->randomFloat(6, 1),
            'quantity' => fake()->randomNumber(),
        ];
    }

    // /**
    //  * @return array|int|string
    //  */
    // private function getUnitId()
    // {
    //     return array_rand(Util::measurements()->toArray(), 1);
    // }
}
