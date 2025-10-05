<?php

if (!defined('DEFINE_CONSTANT')) {
    define('DEFINE_CONSTANT', 'DEFINE_CONSTANT');

    define('ADD_PAGE_SIZE', 2);
    define('PAGE_SIZE', 25);
    define('INPUT_MAX_LENGTH', 255);

    define('DATE_STRING_FORMAT', 'd/m/Y');

    /** List roles of application */
    define('SUPER_ADMIN', 9);
    /** DECIMAL */
    define('DECIMAL', 8);
    define('ADD_ZERO_BEFORE_NUMBER', '%06d');
    define('PLUS_NUMBER_KEY', '1');
    define('ZERO_MONEY', '0');
    define('CONSUMPTION_TAX', 8);
    define('ROUNDING_DECIMAL', 2);
    /** Save path file */
    define('SAVE_PATH_FILE_PREVIEW_REVENUE_AGENT', 'file/preview/revenue/agent/');
    define('SAVE_PATH_FILE_PREVIEW_REVENUE_PRODUCT', 'file/preview/revenue/product/');
    define('SAVE_PATH_FILE_CSV_REVENUE_AGENT', 'file/csv/revenue/agent/');
    define('SAVE_PATH_FILE_CSV_REVENUE_PRODUCT', 'file/csv/revenue/product/');
    define('SAVE_PATH_FILE_CSV_RECEIPT', 'file/csv/receipt/');
    define('SAVE_PATH_FILE_CSV_RECEIPT_MARUTO', 'file/csv/receipt-maruto/');
    define('SAVE_PATH_FILE_CSV_AGENT', 'file/csv/agents/');
    define('SAVE_PATH_FILE_CSV_PRODUCT', 'file/csv/products/');
    define('SAVE_PATH_FILE_PRINT_RECEIPT', 'file/print/receipts/');
    define('SAVE_PATH_FILE_PRINT_RECEIPT_MARUTO', 'file/print/receipts_maruto/');
    define('SAVE_PATH_FILE_PRINT_REVENUE_AGENT', 'file/print/revenue/agent/');
    define('SAVE_PATH_FILE_PRINT_REVENUE_PRODUCT', 'file/print/revenue/product/');
    define('SAVE_PATH_FILE_PRINT_N335_RECEIPT', 'file/print/n335/receipt/');
    define('SAVE_PATH_FILE_PRINT_BILLING_AGENT_YEAR_MONTH', 'file/print/billings/agent-year-month/');
    define('SAVE_PATH_FILE_PRINT_BILLING_BY_YEAR_MONTH', 'file/print/billings/list-by-year-month/');
    define('SAVE_PATH_FILE_PRINT_BILLING_LIST_COLLATIONS', 'file/print/billings/list-collations/');
    define('SAVE_PATH_FILE_PRINT_LIST_BY_PATCH', 'file/print/billings/list-by-patch/');

    /**
     * Set file print
    */
    define('FORE_FILE_PAGE', 2);
    define('TWO_FILE_PAGE', 1);

    /** Filename pdf */
    define('FILENAME_RECEIPT', '売上伝票詳細');
    define('FILENAME_RECEIPT_PAYMENT_REQUEST', '納品書の支払いを要求する');
    define('FILENAME_RECEIPT_MARUTO', '売上伝票詳細');
}
