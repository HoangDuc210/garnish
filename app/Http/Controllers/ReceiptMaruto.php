<?php

namespace App\Http\Controllers;

use App\Enums\PaginationPage;
use App\Enums\Receipt\Type;
use App\Http\Requests\Receipt\ReceiptMarutoRequest;
use App\Http\Requests\Receipt\ReceiptMarutoStoreRequest;
use App\Services\CompanyService;
use App\Services\ReceiptService;
use App\Services\Traits\OptionExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReceiptMaruto extends Controller
{
    use OptionExport;

    /**
     * @var \App\Services\ReceiptService
     * @var \App\Services\CompanyService
     */
    protected $receiptService;
    protected $companyService;

    /**
     * @param \App\Services\ReceiptService $receiptService
     * @param \App\Services\CompanyService $companyService
     */
    public function __construct(
        ReceiptService $receiptService,
        CompanyService $companyService
    ) {
        $this->receiptService = $receiptService;
        $this->companyService = $companyService;
    }
    /**
     * Return view list receipt
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(ReceiptMarutoRequest $request)
    {
        $this->getDataExportCsv(
            [
                'url' => route(RECEIPT_MARUTO_EXPORT_LIST_CSV_ROUTE),
                'btn' => 'receipt-maruto-export-csv'
            ]
        );
        $this->getDataSelectLimit(route(RECEIPT_MARUTO_ROUTE));
        $this->removesItem(route(RECEIPT_MARUTO_REMOVES_ROUTE));
        $dataOptions = $this->getDataOptionExport();


        $assign = [
            'receipts' => $this->receiptService->search($request->all()),
            'dataOptions' => $dataOptions,
        ];

        return view('receipt-maruto.index', $assign);
    }

    /**
     * return view create
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $id = null;
        $transactionDate = $this->receiptService->getTransactionDateDefault(Type::ChainStore);;
        $assign = [
            'transactionDate' => $transactionDate,
        ];
        $codeReceipt = $this->receiptService->getIncrementalCode(Type::ChainStore);
        if (empty(request()->old()) || old('id') != $id) {
            $this->flashSession(['code' => $codeReceipt]);
        }
        return view('receipt-maruto.create', $assign);
    }

    /**
     * return view edit
     *
     * @param integer
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id, ReceiptMarutoRequest $request)
    {
        $receipt = $this->receiptService->detail($id, $request);
        $receipt['products'] = $receipt->receiptDetails->toArray();
        $receipt['agent'] = $receipt->agent;
        $transactionDate = Carbon::now()->format('Y/m/d');
        $assign = [
            'company' => $this->companyService->company(),
            'transactionDate' => $transactionDate,
        ];
        if (empty(request()->old()) || old('id') != $id) {
            $this->flashSession($receipt);
        }

        return view('receipt-maruto.edit', $assign);
    }

    /**
     * Create a new receipt or update receipt by form data
     *
     * @param ReceiptMarutoStoreRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ReceiptMarutoStoreRequest $request)
    {
        $result = $this->receiptService->store($request);

        if (!$result) {
            $message = [
                'errors' => [
                    'code' => ['指定の伝票番号は既に使用されています。']
                ]
                ];
            return response()->json($message, 500);
        }
        return response()->json(['url' => route(RECEIPT_MARUTO_DETAIL_ROUTE, $result->id)]);
    }

    /**
     * update data
     *
     * @param \Illuminate\Http\Request $request
     * @param Request
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function update(Request $request, $id)
    {
        return view('receipts.index');
    }

    /**
     * Delete a receipt
     *
     * @param \Illuminate\Http\Request $request
     * @param id $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, $id)
    {
        $this->receiptService->delete($id);

        return redirect()->route(RECEIPT_MARUTO_ROUTE);
    }

    /**
     * return view edit
     *
     * @param integer
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function detail($id, ReceiptMarutoRequest $request)
    {
        $receipt = $this->receiptService->detail($id, $request);

        $this->getDataExportCsv(
            [
                'id' => $receipt->id,
                'url' => route(RECEIPT_MARUTO_AGENT_EXPORT_CSV_ROUTE),
                'btn' => 'detail-receipt-maruto-export-csv'
            ]
        );
        $this->getDataPrintN335(
            [
                'id' => $receipt->id,
                'url' => route(RECEIPT_MARUTO_DETAIL_PRINT_ROUTE),
                'btn' => 'detail-receipt-maruto-print'
            ]
        );
        $this->getDataPrevPage();
        $this->getDataNextPage();
        $this->getCheckboxNextPrevPage($id, route(RECEIPT_MARUTO_PREV_NEXT_PAGE_ROUTE));
        $this->btnEdit(route(RECEIPT_MARUTO_EDIT_ROUTE, $id));
        $this->btnCreate(route(RECEIPT_MARUTO_CREATE_ROUTE));
        $dataOptions = $this->getDataOptionExport();
        $assign = [
            'receipt' => $receipt,
            'receiptDetails' => $receipt->receiptDetails,
            'agent' => $receipt->agent,
            'company' => $this->companyService->company(),
            'dataOptions' => $dataOptions,
        ];

        return view('receipt-maruto.detail', $assign);
    }

    /**
     * return view duplicate
     *
     * @param integer
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function duplicate($id, ReceiptMarutoRequest $request)
    {
        $receipt = $this->receiptService->detail($id, $request);

        $receipt['transaction_date'] = $receipt->transaction_date_fm;
        $receipt['price_total_product'] = $receipt->price_total_product;
        $receipt['agent']['code'] = $receipt->agent->code;
        $receipt['products'] = $receipt->receiptDetails->toArray();
        $receipt['price_total_product'] = number_format($receipt->price_total_product);
        $receipt['products'] = $receipt->receiptDetails->toArray();
        $receipt['consumption_tax'] = $receipt->consumption_tax;
        $receipt['total_receipt_amount'] = $receipt->total_receipt_amount;
        $transactionDate = Carbon::now()->format('Y/m/d');

        unset($receipt['id']);
        unset($receipt['code']);

        if (empty(request()->old()) || old('id') != $id) {
            $this->flashSession($receipt);
        }

        $assign = [
            'company' => $this->companyService->company(),
            'transactionDate' => $transactionDate,
        ];

        return view('receipt-maruto.duplicate', $assign);
    }

    /**
     * Export list csv
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string $url
     */
    public function exportListReceiptMarutoCsv(Request $request)
    {
        app()->setLocale('ja');
        $url = $this->receiptService->exportListReceiptMarutoCsv($request);

        return response()->json(['result' => $url], 200);
    }

    /**
     * Export list csv
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string $url
     */
    public function exportDetailReceiptMarutoCsv(Request $request)
    {
        app()->setLocale('ja');
        $url = $this->receiptService->exportDetailReceiptMarutoCsv($request);

        return response()->json(['result' => $url], 200);
    }

    /**
     * Print receipt detail
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string $url
     */
    public function printReceiptMaruto(Request $request)
    {
        app()->setLocale('ja');
        $files = $this->receiptService->printReceiptMaruto($request);

        return response()->json(['result' => $files], 200);
    }

    /*
     * Update status print
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string $receipt
     */
    public function searchAjax(Request $request)
    {
        $result = $this->receiptService->searchAjax($request);
        return response()->json(['result' => $result ? $result : []], 200);
    }

    /**
     * Get url prev next page
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string $result
     */
    public function getUrlPrevNextPage(Request $request)
    {
        $result = $this->receiptService->getUrlPrevNextPage($request);
        return response()->json(['result' => $result ? $result : []], 200);
    }

    /**
     * Remove receipts
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function removes(Request $request)
    {
        $request['type_code'] = Type::ChainStore;
        $this->receiptService->removes($request);

        return response()->json(['message' => trans('receipt.deleted')], 200);
    }
}
