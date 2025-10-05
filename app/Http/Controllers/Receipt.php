<?php

namespace App\Http\Controllers;

use App\Enums\PaginationPage;
use App\Enums\Receipt\Type;
use App\Http\Requests\Receipt\ReceiptRequest;
use App\Http\Requests\Receipt\ReceiptStoreRequest;
use App\Services\CompanyService;
use App\Services\ReceiptService;
use App\Services\Traits\OptionExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
class Receipt extends Controller
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
    public function index(ReceiptRequest $request)
    {
        $this->getDataExportCsv(
            [
                'url' => route(RECEIPT_EXPORT_LIST_CSV_ROUTE),
                'btn' => 'receipt-export-list-csv'
            ]
        );
        $this->getDataSelectLimit(route(RECEIPT_ROUTE));
        $this->removesItem(route(RECEIPT_REMOVES_ROUTE));
        $dataOptions = $this->getDataOptionExport();

        $assign = [
            'receipts' => $this->receiptService->search($request->all()),
            'dataOptions' => $dataOptions,
        ];

        return view('receipts.index', $assign);
    }

    /**
     * return view create
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create($id = null)
    {
        $id = null;
        $transactionDate = $this->receiptService->getTransactionDateDefault(Type::Common);
        $assign = [
            'company' => $this->companyService->company(),
            'transactionDate' => $transactionDate,
        ];

        if (empty(request()->old()) || old('id') != $id) {
            $this->flashSession(['code' => $this->receiptService->getIncrementalCode(Type::Common)]);
        }
        return view('receipts.create', $assign);
    }

    /**
     * return view edit
     *
     * @param integer
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id, Request $request)
    {
        $receipt = $this->receiptService->detail($id, $request);
        $receipt['products'] = $receipt->receiptDetails->toArray();
        $receipt['agent'] = $receipt->agent;
        $transactionDate = Carbon::now()->format('Y/m/d');

        if (empty(request()->old()) || old('id') != $id) {
            $this->flashSession($receipt);
        }

        $assign = [
            'company' => $this->companyService->company(),
            'transactionDate' => $transactionDate,
        ];

        return view('receipts.edit', $assign);
    }

    /**
     * Create a new receipt or update receipt by form data
     *
     * @param ReceiptStoreRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ReceiptStoreRequest $request)
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
        return response()->json(['url' => route(RECEIPT_DETAIL_ROUTE, $result->id)]);
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

        return redirect()->route(RECEIPT_ROUTE);
    }

    /**
     * return view edit
     *
     * @param integer
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function detail($id, Request $request)
    {
        $receipt = $this->receiptService->detail($id, $request);

        $this->getDataExportCsv(
            [
                'id' => $receipt->id,
                'url' => route(RECEIPT_AGENT_EXPORT_CSV_ROUTE),
                'btn' => 'detail-receipt-export-csv'
            ]
        );
        $this->getDataPrint(
            [
                'id' => $receipt->id,
                'url' => route(RECEIPT_DETAIL_PRINT_ROUTE),
                'btn' => 'detail-receipt-print'
            ]
        );
        $this->getDataPrintN335(
            [
                'id' => $receipt->id,
                'url' => route(RECEIPT_PRINT_N335_ROUTE),
                'btn' => 'receipt-print-n335'
            ]
        );
        $this->getDataPrevPage();
        $this->getDataNextPage();
        $this->getCheckboxNextPrevPage($id, route(RECEIPT_PREV_NEXT_PAGE_ROUTE));
        $this->btnEdit(route(RECEIPT_EDIT_ROUTE, $id));
        $this->btnCreate(route(RECEIPT_CREATE_ROUTE));
        $dataOptions = $this->getDataOptionExport();

        $assign = [
            'receipt' => $receipt,
            'receiptDetails' => $receipt->receiptDetails,
            'agent' => $receipt->agent,
            'company' => $this->companyService->company(),
            'dataOptions' => $dataOptions,
        ];

        return view('receipts.detail', $assign);
    }

    /**
     * return view edit
     *
     * @param integer
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function duplicate($id, Request $request)
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

        return view('receipts.duplicate', $assign);
    }

    /**
     * Export list csv
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string $url
     */
    public function exportListCsv(Request $request)
    {
        //Set locale japanese
        app()->setLocale('ja');
        $url = $this->receiptService->exportListCsv($request);

        return response()->json(['result' => $url], 200);
    }

    /**
     * Export csv
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string $url
     */
    public function exportCsv(Request $request)
    {
        app()->setLocale('ja');
        $url = $this->receiptService->exportCsv($request);

        return response()->json(['result' => $url], 200);
    }

    /**
     * Print receipt detail
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string $url
     */
    public function printReceipt(Request $request)
    {
        app()->setLocale('ja');
        $url = $this->receiptService->printReceipt($request);

        return response()->json(['result' => $url], 200);
    }

    /**
     * Update status print
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string $url
     */
    public function updatePrintStatus(Request $request)
    {
        $this->receiptService->updatePrintStatus($request);
        return response()->json(['message' => trans('receipt.updated')], 200);
    }

    /**
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
     * Print n335 receipt
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string $urls
     */
    public function printN335Receipt(Request $request)
    {
        $files = $this->receiptService->printN335Receipt($request);

        return response()->json(['result' => $files], 200);
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
        $request['type_code'] = Type::Common;
        $this->receiptService->removes($request);

        return response()->json(['message' => trans('receipt.deleted')], 200);
    }
}
