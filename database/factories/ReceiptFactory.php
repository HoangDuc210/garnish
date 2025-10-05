<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\Receipt\Type;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\reseipts>
 */
class ReceiptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $agentIds = [
            1,
            2,
            3,
            4,
        ];
        $dateSeeds = [
            '2023/01/01',
            '2023/01/02',
            '2023/01/03',

            '2022/12/01',
            '2022/12/02',
        ];

        return [
            'code' => $this->faker->numerify('#######'),
            'type_code' => Type::getRandomValue(),
            'agent_id' => $this->faker->randomElement($agentIds),
            'transaction_date' => $this->faker->randomElement($dateSeeds),
            'print_status' => $this->faker->randomElement([0, 1]),
            'memo' => "一般に信じられていることとは反対に、Lorem Ipsum は単なるランダム テキストではありません。 紀元前 45 年の古典ラテン文学にルーツがあり、2000 年以上前のものです。",
        ];
    }
}
