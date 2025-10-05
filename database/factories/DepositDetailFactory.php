<?php

namespace Database\Factories;

use App\Enums\Deposit\Type;
use App\Models\Agent;
use App\Models\Deposit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DepositDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $depositIds = Deposit::pluck('id');
        $agentIds = Agent::all()->pluck('id')->toArray();
        return [
            'deposit_id' => $this->faker->randomElement($depositIds),
            'billing_agent_id' => $this->faker->randomElement($agentIds),
            'type_code' => Type::getRandomValue(),
            'payment_date' => Carbon::now()->addMonth()->format('Y/m/d'),
            'amount' => $this->faker->randomElement(['1000', '2000', '3000', '4000', '5000', '6000']),
            'memo' => "一般に信じられていることとは反対に、Lorem Ipsum は単なるランダム テキストではありません。 紀元前 45 年の古典ラテン文学にルーツがあり、2000 年以上前のものです。",

        ];
    }
}
