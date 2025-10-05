<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => '株式会社ガーニッシュ',
            'name_kana' => '株式会社ガーニッシュ',
            'post_code' => '331-0811',
            'address' => 'さいたま市北区吉野町2-13-2',
            'address_more' => '',
            'tel' => '048-778-8174',
            'fax' => '048-778-8174',
            'bank_account' => '埼玉りそな銀行 鷲宮支店 普通預金 3965637'
        ];
    }
}
