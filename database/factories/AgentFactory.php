<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\Agent\FractionRounding;
use App\Enums\Agent\TaxFractionRounding;
use App\Enums\Agent\TaxTaxationMethod;
use App\Enums\Agent\TaxType;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Agent>
 */
class AgentFactory extends Factory
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
        ];

        return [
            'name' => fake()->name(),
            'code' => $this->faker->numerify('#######'),
            'name_kana' => fake()->name(),
            'post_code' => fake()->postcode(),
            'address' => fake()->address(),
            'address_more' => fake()->streetAddress(),
            'tel' => fake()->phoneNumber(),
            'fax' => fake()->phoneNumber(),
            'closing_date' => fake()->numberBetween($min = 1, $max = 31),
            'fraction_rounding_code' => FractionRounding::getRandomValue(),
            'tax_type_code' => TaxType::getRandomValue(),
            'tax_fraction_rounding_code' => TaxFractionRounding::getRandomValue(),
            'tax_taxation_method_code' => TaxTaxationMethod::getRandomValue(),
            'collection_rate' => $this->faker->randomElement([0, 10]),

            'billing_agent_id' => $this->faker->randomElement($billingAgentIds),
            'billing_source_company_id' => '1',
        ];
    }
}
