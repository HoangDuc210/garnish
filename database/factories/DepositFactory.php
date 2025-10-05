<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\Deposit\Type;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DepositFactory>
 */
class DepositFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $billingAgentIds = [
            1,
            2,
            3,
        ];
        $amountSeeds = [
            1000,
            2000,
            3000,
            4000,
            5000,
            6000,
            7000,
            8000,
            9000,
        ];
        $dateSeeds = [
            '2022/11/01',
            '2022/11/02',
            '2022/11/03',
            '2021/12/01',
            '2021/12/02',
        ];

        return [
            'billing_agent_id' => $this->faker->randomElement($billingAgentIds),
            'type_code' => Type::getRandomValue(),
            'payment_date' => $this->faker->randomElement($dateSeeds),
            'amount' => $this->faker->randomElement($amountSeeds),
            'memo' => "一般に信じられていることとは反対に、Lorem Ipsum は単なるランダム テキストではありません。 紀元前 45 年の古典ラテン文学にルーツがあり、2000 年以上前のものです。",
        ];
    }
}
