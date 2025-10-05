<?php

//Array of Japanese
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

//Array of Vietnamese
$arrVietnamese = [
    'product_status' => [
        'DISABLED' => 'Không có hiệu lực',
        'AVAILABLE' => 'Có sẵn',
        'OUT_OF_STOCK' => 'Hết hàng',
    ],
    'tax_category' => [
        'TAX_INCLUDED' => 'Thuế bao gồm',
        'TAX_EXCLUDED' => 'Thuế không bao gồm',
        'TAX_EXEMPTED' => 'Miễn thuế',
    ],
    'price_format' => [
        'FOUR_DOWN_FIVE_UP' => 'Làm tròn',
        'ROUNDING_UP' => 'Làm tròn lên',
        'TRUNCATION' => 'Làm tròn xuống',
    ],
    'role' => [
        'STAFF' => 'Nhân viên',
        'ADMIN' => 'Quản trị viên'
    ],
    'user_status' => [
        'DISABLED' => 'Không có hiệu lực',
        'AVAILABLE' => 'Có sẵn',
    ],
    'agent_status' => [
        'DISABLED' => 'Không có hiệu lực',
        'ACTIVE' => 'Có sẵn',
    ],
    'send_money_type' => [
        'CASH' => 'Tiền mặt',
        'CHECK' => 'Séc',
        'BANK_TRANSFER' => 'Chuyển khoản',
        'COMMISSION' => 'Phí',
        'HAND_PRINT' => 'Sổ tiết kiệm',
        'ADJUSTMENT' => 'Điều chỉnh tiền gửi',
    ],
    'receipt_filter' => [
        'DISABLED' => 'Bán hàng',
        'NEW' => 'Mới',
    ],
    'print_status' => [
        'PRINT' => 'Chưa in',
        'PRINTED' => 'Đã in',
    ],
    'pagination_page' => [
        'PAGE_SIZE_25' => '25',
        'PAGE_SIZE_50' => '50',
        'PAGE_SIZE_100' => '100',
    ],
    'taxation_method' => [
        'BILLING' => 'Thuế theo hóa đơn',
        'VOUCHER' => 'Thuế theo phiếu',
        'ITEMIZED' => 'Thuế theo chi tiết',
    ],
    'print_type' => [
        'SET_OF_4' => '4 tờ',
        'SET_OF_2' => '2 tờ',
    ],
];

return $arrVietnamese;
