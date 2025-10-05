<?php

$arrJapan =  [
    'product_status' => [
        'DISABLED' => '無効',
        'AVAILABLE' => '在庫あり',
        'OUT_OF_STOCK' => '在庫切れ',
    ],
    'tax_category' => [
        'TAX_INCLUDED' => '内税',
        'TAX_EXCLUDED' => '外税',
        'TAX_EXEMPTED' => '非課税',
    ],
    'price_format' => [
        'FOUR_DOWN_FIVE_UP' => '四捨五入',
        'ROUNDING_UP' => '切り上げ',
        'TRUNCATION' => '切り捨て',
    ],
    'role' => [
        'STAFF' => '使用者',
        'ADMIN' => '管理者'
    ],
    'user_status' => [
        'DISABLED' => '無効',
        'AVAILABLE' => '在庫あり',
    ],
    'agent_status' => [
        'DISABLED' => '無効',
        'ACTIVE' => '在庫あり',
    ],
    'send_money_type' => [
        'CASH' => '現金',
        'CHECK' => '小切手',
        'BANK_TRANSFER' => '振込',
        'COMMISSION' => '手数料',
        'HAND_PRINT' => '手形',
        'ADJUSTMENT' => '入金調整',
    ],
    'receipt_filter' => [
        'DISABLED' => '売上',
        'NEW' => 'NEW',
    ],
    'print_status' => [
        'PRINT' => '未印刷',
        'PRINTED' => '印刷済み',
    ],
    'pagination_page' => [
        'PAGE_SIZE_25' => '25',
        'PAGE_SIZE_50' => '50',
        'PAGE_SIZE_100' => '100',
    ],
    'taxation_method' => [
        'BILLING' => '請求課税',
        'VOUCHER' => '伝票課税',
        'ITEMIZED' => '明細課税',
    ],
    'print_type' => [
        'SET_OF_4' => '4枚セット',
        'SET_OF_2' => '2枚セット',
    ]
];

//translation to English
$arrEnglish = [
    'product_status' => [
        'DISABLED' => 'Disabled',
        'AVAILABLE' => 'Available',
        'OUT_OF_STOCK' => 'Out of stock',
    ],
    'tax_category' => [
        'TAX_INCLUDED' => 'Tax included',
        'TAX_EXCLUDED' => 'Tax excluded',
        'TAX_EXEMPTED' => 'Tax exempted',
    ],
    'price_format' => [
        'FOUR_DOWN_FIVE_UP' => 'Four down five up',
        'ROUNDING_UP' => 'Rounding up',
        'TRUNCATION' => 'Truncation',
    ],
    'role' => [
        'STAFF' => 'Staff',
        'ADMIN' => 'Admin'
    ],
    'user_status' => [
        'DISABLED' => 'Disabled',
        'AVAILABLE' => 'Available',
    ],
    'agent_status' => [
        'DISABLED' => 'Disabled',
        'ACTIVE' => 'Active',
    ],
    'send_money_type' => [
        'CASH' => 'Cash',
        'CHECK' => 'Check',
        'BANK_TRANSFER' => 'Bank transfer',
        'COMMISSION' => 'Commission',
        'HAND_PRINT' => 'Hand print',
        'ADJUSTMENT' => 'Adjustment',
    ],
    'receipt_filter' => [
        'DISABLED' => 'Sales',
        'NEW' => 'New',
    ],
    'print_status' => [
        'PRINT' => 'Not printed',
        'PRINTED' => 'Printed',
    ],
    'pagination_page' => [
        'PAGE_SIZE_25' => '25',
        'PAGE_SIZE_50' => '50',
        'PAGE_SIZE_100' => '100',
    ],
    'taxation_method' => [
        'BILLING' => 'Billing',
        'VOUCHER' => 'Voucher',
        'ITEMIZED' => 'Itemized',
    ],
    'print_type' => [
        'SET_OF_4' => 'Set of 4',
        'SET_OF_2' => 'Set of 2',
    ]
];

return $arrEnglish;
