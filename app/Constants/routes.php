<?php

if (!defined('DEFINE_ROUTER')) {
    //Auth
    define('LOGIN_ROUTE', 'auth.login');
    define('LOGOUT_ROUTE', 'auth.logout');

    //Dashboard
    define('DASHBOARD_ROUTE', 'dashboard');

    //Products
    define('PRODUCT_ROUTE', 'product');
    define('PRODUCT_CREATE_ROUTE', 'product.create');
    define('PRODUCT_EDIT_ROUTE', 'product.edit');
    define('PRODUCT_STORE_ROUTE', 'product.store');
    define('PRODUCT_DELETE_ROUTE', 'product.delete');
    define('PRODUCT_SEARCH_AJAX_ROUTE', 'product.search.ajax');
    define('PRODUCT_EXPORT_CSV_ROUTE', 'product.export.csv');
    define('PRODUCT_MODAL_SEARCH_AJAX_ROUTE', 'product.modal.ajax');

    //Agents
    define('AGENT_ROUTE', 'agent');
    define('AGENT_CREATE_ROUTE', 'agent.create');
    define('AGENT_EDIT_ROUTE', 'agent.edit');
    define('AGENT_STORE_ROUTE', 'agent.store');
    define('AGENT_DELETE_ROUTE', 'agent.delete');
    define('AGENT_SEARCH_AJAX_ROUTE', 'agent.search.ajax');
    define('AGENT_BILLING_AGENTS_ROUTE', 'agent.billing.agents');
    define('AGENT_EXPORT_CSV_ROUTE', 'agent.export.csv');
    define('AGENT_MODAL_SEARCH_AJAX_ROUTE', 'agent.modal.search.ajax');

    //Measurement Units
    define('UNIT_ROUTE', 'unit.route');
    define('UNIT_SEARCH_AJAX_ROUTE', 'unit.search.ajax');

    //Users
    define('USER_ROUTE', 'user.route');
    define('USER_CREATE_ROUTE', 'user.create');
    define('USER_EDIT_ROUTE', 'user.edit');
    define('USER_STORE_ROUTE', 'user.store');
    define('USER_DELETE_ROUTE', 'user.delete');

    //Receipts
    define('RECEIPT_ROUTE', 'receipt');
    define('RECEIPT_CREATE_ROUTE', 'receipt.create');
    define('RECEIPT_EDIT_ROUTE', 'receipt.edit');
    define('RECEIPT_STORE_ROUTE', 'receipt.store');
    define('RECEIPT_DELETE_ROUTE', 'receipt.delete');
    define('RECEIPT_DETAIL_ROUTE', 'receipt.detail');
    define('RECEIPT_DUPLICATE_ROUTE', 'receipt.duplicate');
    define('RECEIPT_EXPORT_LIST_CSV_ROUTE', 'receipt.export.list.csv');
    define('RECEIPT_AGENT_EXPORT_CSV_ROUTE', 'receipt.agent.export.csv');
    define('RECEIPT_DETAIL_PRINT_ROUTE', 'receipt.agent.print');
    define('RECEIPT_UPDATE_PRINT_STATUS_ROUTE', 'receipt.update.print.status');
    define('RECEIPT_SEARCH_AJAX_ROUTE', 'receipt.search.ajax');
    define('RECEIPT_PREV_NEXT_PAGE_ROUTE', 'receipt.prev.next.page');
    define('RECEIPT_PRINT_N335_ROUTE', 'receipt.print.n335');
    define('RECEIPT_REMOVES_ROUTE', 'receipt.removes');

    //RECEIPTs maruto
    define('RECEIPT_MARUTO_ROUTE', 'receipt.maruto.route');
    define('RECEIPT_MARUTO_CREATE_ROUTE', 'receipt.maruto.create');
    define('RECEIPT_MARUTO_EDIT_ROUTE', 'receipt.maruto.edit');
    define('RECEIPT_MARUTO_STORE_ROUTE', 'receipt.maruto.store');
    define('RECEIPT_MARUTO_DELETE_ROUTE', 'receipt.maruto.delete');
    define('RECEIPT_MARUTO_DETAIL_ROUTE', 'receipt.maruto.detail');
    define('RECEIPT_MARUTO_DUPLICATE_ROUTE', 'receipt.maruto.duplicate');
    define('RECEIPT_MARUTO_EXPORT_LIST_CSV_ROUTE', 'receipt.maruto.export.list.csv');
    define('RECEIPT_MARUTO_AGENT_EXPORT_CSV_ROUTE', 'receipt.maruto.agent.export.csv');
    define('RECEIPT_MARUTO_DETAIL_PRINT_ROUTE', 'receipt.maruto.detail.print');
    define('RECEIPT_MARUTO_SEARCH_AJAX_ROUTE', 'receipt.maruto.search.ajax');
    define('RECEIPT_MARUTO_PREV_NEXT_PAGE_ROUTE', 'receipt.maruto.prev.next.page');
    define('RECEIPT_MARUTO_REMOVES_ROUTE', 'receipt.maruto.removes');

    //Company
    define('COMPANY_ROUTE', 'company');
    define('COMPANY_STORE_ROUTE', 'company.create');

    // Deposit
    define('DEPOSIT_ROUTE', 'deposit');
    define('DEPOSIT_CREATE_ROUTE', 'deposit.create');
    define('DEPOSIT_EDIT_ROUTE', 'deposit.edit');
    define('DEPOSIT_STORE_ROUTE', 'deposit.store');
    define('DEPOSIT_UPDATE_ROUTE', 'deposit.update');
    define('DEPOSIT_DELETE_ROUTE', 'deposit.delete');
    define('DEPOSIT_SEARCH_AJAX_ROUTE', 'deposit.search.ajax');
    define('DEPOSIT_AMOUNT_AGENT_ROUTE', 'deposit.count.agent');

    // Access History
    define('ACCESS_HISTORY_ROUTE', 'access.history');
    define('ACCESS_HISTORY_CREATE_ROUTE', 'access.history.create');
    define('ACCESS_HISTORY_EDIT_ROUTE', 'access.history.edit');
    define('ACCESS_HISTORY_STORE_ROUTE', 'access.history.store');
    define('ACCESS_HISTORY_DELETE_ROUTE', 'access.history.delete');

    //Payment report
    define('PAYMENT_REPORT_ROUTE', 'payment.report');

    //Bulk invoicing
    define('BULK_INVOICING_ROUTE', 'bulk.invoicing');

    //Payment request list
    define('PAYMENT_REQUEST_LIST_ROUTE', 'payment.request.list');

    // Billing
    define('BILLING_SEARCH_ROUTE', 'billing.search');
    define('BILLING_LIST_BY_BILLING_AGENT_YEAR_MONTH_ROUTE', 'billing.listByBillingAgentYearMonth');
    define('BILLING_STORE_BY_BILLING_AGENT_YEAR_MONTH_ROUTE', 'billing.storeByBillingAgentYearMonth');
    define('BILLING_EXPORT_CSV_BY_BILLING_AGENT_YEAR_MONTH_ROUTE', 'billing.exportCsvByBillingAgentYearMonth');

    define('BILLING_LIST_BY_YEAR_MONTH_ROUTE', 'billing.listByYearMonth');
    define('BILLING_STORE_BY_YEAR_MONTH_ROUTE', 'billing.storeByYearMonth');
    define('BILLING_EXPORT_CSV_BY_YEAR_MONTH_ROUTE', 'billing.exportCsvByYearMonth');

    define('BILLING_LIST_BILLING_AGENT_COLLATIONS_ROUTE', 'billing.listBillingAgentCollations');
    define('BILLING_EXPORT_CSV_BILLING_AGENT_COLLATIONS_ROUTE', 'billing.exportCsvBillingAgentCollations');

    define('BILLING_LIST_BY_BATCH_ROUTE', 'billing.listByBatch');
    define('BILLING_STORE_BY_BATCH_ROUTE', 'billing.storeByBatch');
    define('BILLING_DEPOSIT_AMOUNT_AGENT_ROUTE', 'billing.deposit.amount.agent');
    define('PRINT_BILLING_AGENT_YEAR_MONTH_ROUTE', 'print.billing.agent.year.month');
    define('PRINT_LIST_BY_YEAR_MONTH_ROUTE', 'print.list.by.year.month');
    define('PRINT_LIST_COLLATIONS_ROUTE', 'print.list.collations');
    define('PREVIEW_LIST_COLLATIONS_ROUTE', 'preview.list.collations');
    define('PRINT_LIST_BY_BATCH_ROUTE', 'print.list.by.batch');
    define('PREVIEW_LIST_BY_BATCH_ROUTE', 'preview.list.by.batch');

    //Revenue
    define('REVENUE_AGENT_ROUTE', 'revenue.agent');
    define('REVENUE_PRODUCT_ROUTE', 'revenue.product');
    define('REVENUE_AGENT_PREVIEW_ROUTE', 'revenue.agent.preview');
    define('REVENUE_PRODUCT_PREVIEW_ROUTE', 'revenue.product.preview');
    define('REVENUE_AGENT_EXPORT_CSV_ROUTE', 'revenue.agent.export.csv');
    define('REVENUE_PRODUCT_EXPORT_CSV_ROUTE', 'revenue.product.export.csv');
    define('REVENUE_AGENT_PRINT_ROUTE', 'revenue.agent.print');
    define('REVENUE_PRODUCT_PRINT_ROUTE', 'revenue.product.print');

}
