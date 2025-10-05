<?php

namespace Database\Factories;

use App\Models\Receipt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReceiptDetailFactory>
 */
class ReceiptDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $priceSeeds = [
            1000,
            2000,
            3000,
        ];
        $receiptIds = Receipt::all()->pluck('id');

        return [
            'receipt_id' => fake()->randomElement($receiptIds),
            'product_id' => fake()->numberBetween($min = 1, $max = 10),
            'unit_id' => fake()->numberBetween($min = 1, $max = 10),
            'price' => $this->faker->randomElement($priceSeeds),
            'quantity' => fake()->numberBetween($min = 1, $max = 10),
            'memo' => "一般に信じられていることとは反対に、Lorem Ipsum は単なるランダム テキストではありません。 紀元前 45 年の古典ラテン文学にルーツがあり、2000 年以上前のものです。",
        ];
    }
}
