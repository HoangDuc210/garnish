<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $masterData = [
            [
                'code' => 'cs',
                'name' => 'CS',
            ],
            [
                'code' => 'hon',
                'name' => '本',
            ],
            [
                'code' => 'ko',
                'name' => '個',
            ],
            [
                'code' => 'kg',
                'name' => 'Kg',
            ],
            [
                'code' => 'pk',
                'name' => 'PK',
            ],
            [
                'code' => 'bundle',
                'name' => '束',
            ],
            [
                'code' => 'bag',
                'name' => '袋',
            ],
            [
                'code' => 'sheet',
                'name' => '枚',
            ],
            [
                'code' => 'chou',
                'name' => '丁',
            ],
            [
                'code' => 'bou',
                'name' => '房',
            ],
            [
                'code' => 'case',
                'name' => '件',
            ],
        ];

        foreach ($masterData as $masterData) {
            Unit::create($masterData);
        }
    }
}
