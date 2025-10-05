<?php

$arrJapan =  [
    'id' => '伝票№',
    'deposit_amount' => '御入金額',
    'total_amount' => '御買上額',
    'memo' => '摘要',
    'day_trading' => 'デイトレーディング',
    'dealer_name' => '販売業者名',
    'code' => '伝票番号',
    'unprinted' => '印刷されていない',
    'printed' => '印刷された',
    'print' => 'クーポンを印刷する',
    'status' => '状態',
    'transaction_date' => '取引日',
    'billing_cycle' => '締日',
    'total' => '伝票合計金額',
    'agent_name' => '得意先名',
    'alert_delete' => 'の伝票を削除しますか？',
    'created' => '売上伝票（通常）を追加いたしました',
    'updated' => '売上伝票（通常）売上伝票（通常）情報が変更されました。',
    'deleted' => '売上伝票（通常）が削除されました。',
    'print_status' => '印刷',
    'description' => '下記のとおり納品いたしました。',
    'description_payment_request' => '下記のとおり御請求申し上げます。',
    'maruto' => [
        'total' => '合計',
        'tax' => '税率',
        'consumption_tax' => '消費税',
        'total_amount' => '総合計',
        'address' => '事務所名'
    ],
    'export' => [
        'code' => '伝票番号',
        'trade_date' => '取引日',
        'customer' => '得意先',
        'total' => '【伝票合計】',
        'quantity' => '数量',
        'product' => [
            'code' => '商品コード',
        ],
    ],
    'agent' => [
        'name' => '得意先名',
    ],
    'total_amount' => '伝票合計',
    'filter' =>[
        'code' => '伝票番号',
        'sort' => '並び順',
    ],
    'print' => [
        'N335' =>[
            'maruto' => [
                'title' => '納　品　書（控）',
                'product' => [
                    'name' => '品名・規格',
                    'quantity' => '数量',
                ],
                'memo' => '備考'
            ],
            'product' => [
                'name' => '品　　　　名',
                'quantity' => '数量',
                'price_total' => '単価(税抜・税込)',
                'price' => '単価',
                'tax' => '税率(%)',
                'memo' => '金額'
            ],
        ],
        'total' => '伝票合計',
    ],
    'csv' => [
        'no' => '商品No.',
    ],
    'quantity' => '数量',
    'total' => '伝票合計金額',
    'agent_name' => '事務所名',
    'delete_all' => '選択されている伝票がありませんので、選択してください。',
    'list_empty' => '納品書はありません!',
    'list_empty_maruto' => 'マルト食品用納品伝票はありません!',
];

//Array English
$arrEnglish = [
    'id' => 'Receipt No.',
    'deposit_amount' => 'Deposit amount',
    'total_amount' => 'Total amount',
    'memo' => 'Memo',
    'day_trading' => 'Day trading',
    'dealer_name' => 'Dealer name',
    'code' => 'Receipt No.',
    'unprinted' => 'Unprinted',
    'printed' => 'Printed',
    'print' => 'Print coupon',
    'status' => 'Status',
    'transaction_date' => 'Transaction date',
    'billing_cycle' => 'Billing cycle',
    'total' => 'Total receipt amount',
    'agent_name' => 'Agent name',
    'alert_delete' => 'Are you sure you want to delete the receipt?',
    'created' => 'Sales receipt (normal) added',
    'updated' => 'Sales receipt (normal) information changed.',
    'deleted' => 'Sales receipt (normal) deleted.',
    'print_status' => 'Print',
    'description' => 'We deliver as follows.',
    'description_payment_request' => 'We request as follows.',
    'maruto' => [
        'total' => 'Total',
        'tax' => 'Tax rate',
        'consumption_tax' => 'Consumption tax',
        'total_amount' => 'Total amount',
        'address' => 'Office name'
    ],
    'export' => [
        'code' => 'Receipt No.',
        'trade_date' => 'Transaction date',
        'customer' => 'Customer',
        'total' => 'Total receipt amount',
        'quantity' => 'Quantity',
        'product' => [
            'code' => 'Product code',
        ],
    ],
    'agent' => [
        'name' => 'Agent name',
    ],
    'total_amount' => 'Total receipt amount',
    'filter' =>[
        'code' => 'Receipt No.',
        'sort' => 'Sort',
    ],

    'total' => 'Total receipt amount',

    'print' => [
        'N335' =>[
            'maruto' => [
                'title' => 'Delivery note (control)',
                'product' => [
                    'name' => 'Product name / specification',
                    'quantity' => 'Quantity',
                ],
                'memo' => 'Memo'
            ],
            'product' => [
                'name' => 'Product name',
                'quantity' => 'Quantity',
                'price_total' => 'Unit price (tax excluded / tax included)',
            ]
        ],
        'total' => 'Total receipt amount',
    ],
    'csv' => [
        'no' => 'Product No',
    ],
    'quantity' => 'Quantity',
    'total' => 'Total receipt amount',
    'agent_name' => 'Office name',
    'delete_all' => 'There are no selected receipts, so please select.',
    'list_empty' => 'Delivery note does not exist',
    'list_empty_maruto' => 'Maruto food delivery note does not exist',
];

return $arrEnglish;
