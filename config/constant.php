<?php

return [
    // models
    'access_history' => [
        'type' => [
            'login' => [
                'code' => 'login',
                'name' => 'login',
                'label' => 'ログイン',
            ],
            'logout' => [
                'code' => 'logout',
                'name' => 'logout',
                'label' => 'ログアウト',
            ],
        ]
    ],
    'agent' => [
        'fraction_rounding' => [
            'four_down_five_up' => [
                'code' => 'four_down_five_up',
                'name' => 'four_down_five_up',
                'label' => '四捨五入',
            ],
            'truncation' => [
                'code' => 'truncation',
                'name' => 'truncation',
                'label' => '切り捨て',
            ],
            'rounding_up' => [
                'code' => 'rounding_up',
                'name' => 'rounding_up',
                'label' => '切り上げ',
            ],
        ],
        'tax_type' => [
            'tax_excluded' => [
                'code' => 'tax_excluded',
                'name' => 'tax_excluded',
                'label' => '外税',
            ],
            'tax_included' => [
                'code' => 'tax_included',
                'name' => 'tax_included',
                'label' => '内税',
            ],
            'tax_exempted' => [
                'code' => 'tax_exemptd',
                'name' => 'tax_exemptd',
                'label' => '非課税',
            ],
        ],
        'tax_fraction_rounding' => [
            'four_down_five_up' => [
                'code' => 'four_down_five_up',
                'name' => 'four_down_five_up',
                'label' => '四捨五入',
            ],
            'truncation' => [
                'code' => 'truncation',
                'name' => 'truncation',
                'label' => '切り捨て',
            ],
            'rounding_up' => [
                'code' => 'rounding_up',
                'name' => 'rounding_up',
                'label' => '切り上げ',
            ],
        ],
        'tax_taxation_method' => [
            'billing' => [
                'code' => 'billing',
                'name' => 'billing',
                'label' => '請求課税',
            ],
            'voucher' => [
                'code' => 'voucher',
                'name' => 'voucher',
                'label' => '伝票課税',
            ],
            'itemized' => [
                'code' => 'itemized',
                'name' => 'itemized',
                'label' => '明細課税',
            ],
        ],
    ],
    'receipt' => [
        'type' => [
            'common' => [
                'code' => 'common',
                'name' => 'common',
                'label' => '通常',
            ],
            'chain_store' => [
                'code' => 'chain_store',
                'name' => 'chain_store',
                'label' => 'チェーンストア',
            ],
        ]
    ],
    'deposit' => [
        'type' => [
            'cash' => [
                'code' => 'cash',
                'name' => 'cash',
                'label' => '現金',
            ],
            'check' => [
                'code' => 'check',
                'name' => 'check',
                'label' => '小切手',
            ],
            'bank_transfer' => [
                'code' => 'bank_transfer',
                'name' => 'bank_transfer',
                'label' => '振込',
            ],
            'commission' => [
                'code' => 'commission',
                'name' => 'commission',
                'label' => '手数料',
            ],
            'hand_print' => [
                'code' => 'hand_print',
                'name' => 'hand_print',
                'label' => '手形',
            ],
            'adjustment' => [
                'code' => 'adjustment',
                'name' => 'adjustment',
                'label' => '入金調整',
            ],
        ]
    ],
];
