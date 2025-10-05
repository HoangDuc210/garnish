<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AccessHistory, Billing, BulkInvoicing, Deposit, PaymentReport, PaymentRequestList, User};

Route::middleware(['can:admin'])->group(function () {
    /**
     * Access History
     */
    Route::resource('access-history', AccessHistory::class)->only(['index', 'create', 'edit', 'store'])
        ->names([
            'index'  => ACCESS_HISTORY_ROUTE,
            'create' => ACCESS_HISTORY_CREATE_ROUTE,
            'edit'   => ACCESS_HISTORY_EDIT_ROUTE,
            'store'  => ACCESS_HISTORY_STORE_ROUTE,
        ]);


    /**
     *  Deposit
     */
    Route::resource('deposits', Deposit::class)->only(['index', 'create', 'store', 'update'])
        ->names([
            'index'  => DEPOSIT_ROUTE,
            'create' => DEPOSIT_CREATE_ROUTE,
            'store'  => DEPOSIT_STORE_ROUTE,
        ]);
    Route::prefix('deposits')->group(function () {
        Route::get('edit/{id}/{billing_agent_id}/{payment_year_month}', [Deposit::class, 'edit'])->name(DEPOSIT_EDIT_ROUTE)->whereNumber('id');
        Route::post('update', [Deposit::class, 'update'])->name(DEPOSIT_UPDATE_ROUTE);
        Route::get('delete/{id}/{billing_agent_id}/{payment_year_month}', [Deposit::class, 'delete'])->name(DEPOSIT_DELETE_ROUTE)->whereNumber('id');
        Route::get('search-ajax', [Deposit::class, 'searchAjax'])->name(DEPOSIT_SEARCH_AJAX_ROUTE);
        Route::post('get-deposit-amount', [Deposit::class, 'getDepositAmountOfAgent'])->name(DEPOSIT_AMOUNT_AGENT_ROUTE);

    });

    /**
     *  Payment report
     */
    Route::resource('payment-report', PaymentReport::class)->only(['index'])
        ->names([
            'index'  => PAYMENT_REPORT_ROUTE,
        ]);


    /**
     *  Bulk Invoicing
     */
    Route::resource('bulk-invoicing', BulkInvoicing::class)->only(['index'])
        ->names([
            'index'  => BULK_INVOICING_ROUTE,
        ]);

    /**
     *  Payment request list
     */
    Route::resource('payment-request-list', PaymentRequestList::class)->only(['index'])
        ->names([
            'index'  => PAYMENT_REQUEST_LIST_ROUTE,
        ]);

    /**
     * Users
     */
    Route::resource('users', User::class)->only(['index', 'create', 'edit', 'store'])
        ->names([
            'index'  => USER_ROUTE,
            'create' => USER_CREATE_ROUTE,
            'edit'   => USER_EDIT_ROUTE,
            'store'  => USER_STORE_ROUTE,
        ]);
    Route::get('delete/{id}', [User::class, 'delete'])->name(USER_DELETE_ROUTE)->whereNumber('id');

    /**
     * Billing
     */
    Route::prefix('billings')->group(function () {
        Route::get('search', [Billing::class, 'search'])->name(BILLING_SEARCH_ROUTE);

        Route::get('list-by-agent-year-month', [Billing::class, 'listByBillingAgentYearMonth'])->name(BILLING_LIST_BY_BILLING_AGENT_YEAR_MONTH_ROUTE);
        Route::post('store-by-agent-year-month', [Billing::class, 'storeByBillingAgentYearMonth'])->name(BILLING_STORE_BY_BILLING_AGENT_YEAR_MONTH_ROUTE);
        Route::post('export-csv-by-agent-year-month', [Billing::class, 'exportCsvByBillingAgentYearMonth'])->name(BILLING_EXPORT_CSV_BY_BILLING_AGENT_YEAR_MONTH_ROUTE);

        Route::get('list-by-year-month', [Billing::class, 'listByYearMonth'])->name(BILLING_LIST_BY_YEAR_MONTH_ROUTE);
        Route::post('store-by-year-month', [Billing::class, 'storeByYearMonth'])->name(BILLING_STORE_BY_YEAR_MONTH_ROUTE);
        Route::post('export-csv-by-year-month', [Billing::class, 'exportCsvByYearMonth'])->name(BILLING_EXPORT_CSV_BY_YEAR_MONTH_ROUTE);

        Route::get('list-collations', [Billing::class, 'listBillingAgentCollations'])->name(BILLING_LIST_BILLING_AGENT_COLLATIONS_ROUTE);
        Route::post('export-csv-collations', [Billing::class, 'exportCsvBillingAgentCollations'])->name(BILLING_EXPORT_CSV_BILLING_AGENT_COLLATIONS_ROUTE);

        Route::get('list-by-batch', [Billing::class, 'listByBatch'])->name(BILLING_LIST_BY_BATCH_ROUTE);
        Route::post('store-by-batch', [Billing::class, 'storeByBatch'])->name(BILLING_STORE_BY_BATCH_ROUTE);

        Route::post('get-deposit-amount', [Billing::class, 'getDepositAmountOfAgent'])->name(BILLING_DEPOSIT_AMOUNT_AGENT_ROUTE);
        //Print billing agent year month
        Route::post('print-billing-agent-year-month', [Billing::class, 'printBillingAgentYearMonth'])->name(PRINT_BILLING_AGENT_YEAR_MONTH_ROUTE);
        //Print list-by-year-month
        Route::post('print-list-by-year-month', [Billing::class, 'printListByYearMonth'])->name(PRINT_LIST_BY_YEAR_MONTH_ROUTE);
        //Print list-collations
        Route::post('print-list-collations', [Billing::class, 'printListCollations'])->name(PRINT_LIST_COLLATIONS_ROUTE);
        //Preview list-collations
        Route::post('preview-list-collations', [Billing::class, 'previewListCollations'])->name(PREVIEW_LIST_COLLATIONS_ROUTE);
        //Print list by batch
        Route::post('print-list-by-batch', [Billing::class, 'printListByBatch'])->name(PRINT_LIST_BY_BATCH_ROUTE);
        //Preview list by batch
        Route::post('preview-list-by-batch', [Billing::class, 'previewListByBatch'])->name(PREVIEW_LIST_BY_BATCH_ROUTE);
    });
});
