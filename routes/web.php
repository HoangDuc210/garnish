<?php

use App\Helpers\Facades\Util;
use App\Http\Controllers\{Product, Agent, Company, User, Receipt, Unit, Billing, ReceiptMaruto, Revenue};
use App\Jobs\RemoveFileJob;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route(RECEIPT_ROUTE, ['transaction_start_date' => Util::addParamReceipt()]);
})->name(DASHBOARD_ROUTE);

/**
 * Product routes
 */
Route::resource('products', Product::class)->only(['index', 'create', 'edit', 'store'])
    ->names([
        'index'  => PRODUCT_ROUTE,
        'create' => PRODUCT_CREATE_ROUTE,
        'edit'   => PRODUCT_EDIT_ROUTE,
        'store'  => PRODUCT_STORE_ROUTE,
    ]);
Route::prefix('products')->group(function () {
    Route::get('delete/{id}', [Product::class, 'delete'])->name(PRODUCT_DELETE_ROUTE)->whereNumber('id');
    Route::get('search-ajax', [Product::class, 'searchAjax'])->name(PRODUCT_SEARCH_AJAX_ROUTE)->whereNumber('id');
    Route::post('export-csv', [Product::class, 'exportCSV'])->name(PRODUCT_EXPORT_CSV_ROUTE);
    Route::get('modal-search-ajax', [Product::class, 'searchModal'])->name(PRODUCT_MODAL_SEARCH_AJAX_ROUTE);

});

/**
 * Agent routes
 */
Route::resource('agents', Agent::class)->only(['index', 'create', 'edit', 'store'])
    ->names([
        'index'  => AGENT_ROUTE,
        'create' => AGENT_CREATE_ROUTE,
        'edit'   => AGENT_EDIT_ROUTE,
        'store'  => AGENT_STORE_ROUTE,
    ]);

Route::prefix('agents')->group(function () {
    Route::get('delete/{id}', [Agent::class, 'destroy'])->name(AGENT_DELETE_ROUTE)->whereNumber('id');
    Route::get('search-ajax', [Agent::class, 'search'])->name(AGENT_SEARCH_AJAX_ROUTE);
    Route::get('billing-agents', [Agent::class, 'getBillingAgents'])->name(AGENT_BILLING_AGENTS_ROUTE);
    Route::post('export-csv', [Agent::class, 'exportCSV'])->name(AGENT_EXPORT_CSV_ROUTE);
    Route::get('modal-search-ajax', [Agent::class, 'searchModal'])->name(AGENT_MODAL_SEARCH_AJAX_ROUTE);
});

/**
 * Measurements unit
 */
Route::prefix('units')->group(function () {
    Route::get('/', [Unit::class, 'index'])->name(UNIT_ROUTE);
    Route::get('search-ajax', [Unit::class, 'searchAjax'])->name(UNIT_SEARCH_AJAX_ROUTE);
});

/**
 * Receipts
 */
Route::resource('receipts', Receipt::class)->only(['index', 'create', 'edit', 'store'])
    ->names([
        'index'  => RECEIPT_ROUTE,
        'create' => RECEIPT_CREATE_ROUTE,
        'edit'   => RECEIPT_EDIT_ROUTE,
        'store'  => RECEIPT_STORE_ROUTE,
    ]);
Route::prefix('receipts')->group(function () {

    Route::get('delete/{id}', [Receipt::class, 'delete'])->name(RECEIPT_DELETE_ROUTE)->whereNumber('id');
    Route::get('detail/{id}', [Receipt::class, 'detail'])->name(RECEIPT_DETAIL_ROUTE)->whereNumber('id');

    //Export CSV list receipt
    Route::post('export-list-csv', [Receipt::class, 'exportListCsv'])->name(RECEIPT_EXPORT_LIST_CSV_ROUTE);

    Route::post('export-csv', [Receipt::class, 'exportCsv'])->name(RECEIPT_AGENT_EXPORT_CSV_ROUTE);

    Route::post('print', [Receipt::class, 'printReceipt'])->name(RECEIPT_DETAIL_PRINT_ROUTE);
    Route::post('update/print-status', [Receipt::class, 'updatePrintStatus'])->name(RECEIPT_UPDATE_PRINT_STATUS_ROUTE);
    Route::get('search-ajax', [Receipt::class, 'searchAjax'])->name(RECEIPT_SEARCH_AJAX_ROUTE);
    Route::post('get-url-prev-next-page', [Receipt::class, 'getUrlPrevNextPage'])->name(RECEIPT_PREV_NEXT_PAGE_ROUTE);
    Route::post('print-n335', [Receipt::class, 'printN335Receipt'])->name(RECEIPT_PRINT_N335_ROUTE);
    Route::post('removes', [Receipt::class, 'removes'])->name(RECEIPT_REMOVES_ROUTE);
});

Route::prefix('product-agent')->group(function(){
    Route::get('search-ajax', [Product::class, 'searchProductAgent']);
});

/**
 * Orders Maruto
 */
Route::resource('receipts-maruto', ReceiptMaruto::class)->only(['index', 'create', 'edit', 'store'])
    ->names([
        'index'  => RECEIPT_MARUTO_ROUTE,
        'create' => RECEIPT_MARUTO_CREATE_ROUTE,
        'edit'   => RECEIPT_MARUTO_EDIT_ROUTE,
        'store'  => RECEIPT_MARUTO_STORE_ROUTE,
    ]);
Route::prefix('receipts-maruto')->group(function () {
    Route::get('delete/{id}', [ReceiptMaruto::class, 'delete'])->name(RECEIPT_MARUTO_DELETE_ROUTE)->whereNumber('id');
    Route::get('detail/{id}', [ReceiptMaruto::class, 'detail'])->name(RECEIPT_MARUTO_DETAIL_ROUTE)->whereNumber('id');

    //Export CSV list receipt
    Route::post('export-list-csv', [ReceiptMaruto::class, 'exportListReceiptMarutoCsv'])->name(RECEIPT_MARUTO_EXPORT_LIST_CSV_ROUTE);

    Route::post('export-csv', [ReceiptMaruto::class, 'exportDetailReceiptMarutoCsv'])->name(RECEIPT_MARUTO_AGENT_EXPORT_CSV_ROUTE);

    Route::post('print', [ReceiptMaruto::class, 'printReceiptMaruto'])->name(RECEIPT_MARUTO_DETAIL_PRINT_ROUTE);
    Route::get('search-ajax', [ReceiptMaruto::class, 'searchAjax'])->name(RECEIPT_MARUTO_SEARCH_AJAX_ROUTE);
    Route::post('get-url-prev-next-page', [ReceiptMaruto::class, 'getUrlPrevNextPage'])->name(RECEIPT_MARUTO_PREV_NEXT_PAGE_ROUTE);
    Route::post('removes', [ReceiptMaruto::class, 'removes'])->name(RECEIPT_MARUTO_REMOVES_ROUTE);
});

/**
 * Company
 */
Route::resource('company', Company::class)->only(['index', 'store'])
    ->names([
        'index'  => COMPANY_ROUTE,
        'store'  => COMPANY_STORE_ROUTE,
    ]);

/**
 * Revenue
 */
Route::prefix('revenue')->group(function () {
    Route::get('agent', [Revenue::class, 'getAgentRevenueList'])->name(REVENUE_AGENT_ROUTE);
    Route::get('product', [Revenue::class, 'getProductRevenueList'])->name(REVENUE_PRODUCT_ROUTE);
    Route::post('agent/preview', [Revenue::class, 'previewAgentRevenue'])->name(REVENUE_AGENT_PREVIEW_ROUTE);
    Route::post('agent/export-csv', [Revenue::class, 'exportCsvAgentRevenue'])->name(REVENUE_AGENT_EXPORT_CSV_ROUTE);
    Route::post('product/preview', [Revenue::class, 'previewProductRevenue'])->name(REVENUE_PRODUCT_PREVIEW_ROUTE);
    Route::post('product/export-csv', [Revenue::class, 'exportCsvProductRevenue'])->name(REVENUE_PRODUCT_EXPORT_CSV_ROUTE);
    Route::post('agent/print', [Revenue::class, 'printAgentRevenue'])->name(REVENUE_AGENT_PRINT_ROUTE);
    Route::post('product/print', [Revenue::class, 'printProductRevenue'])->name(REVENUE_PRODUCT_PRINT_ROUTE);
});

Route::get('test', function () {
    dd(Storage::exists('file'));
});
// Language Switch
Route::get('language/{language}', [\App\Http\Controllers\Admin\LanguageController::class, 'switch'])->name('language.switch');

//Get current language
Route::get('language', function () {
    return App::getLocale();
});
//Php info
Route::get('phpinfo', function () {
    phpinfo();
});
