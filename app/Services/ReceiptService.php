<?php

namespace App\Services;

use App\Enums\PrintStatus;
use App\Enums\PrintType;
use App\Enums\Receipt\Type;
use App\Events\ProductAgentEvent;
use App\Events\UpdateProductReceiptEvent;
use App\Exports\PrintN335ReceiptExport;
use App\Exports\PrintPaymentRequestReceiptExport;
use App\Exports\PrintReceiptExport;
use App\Exports\PrintReceiptMarutoExport;
use App\Exports\ReceiptCsvExport;
use App\Exports\ReceiptListExport;
use App\Exports\ReceiptMarutoDetailExport;
use App\Exports\ReceiptMarutoListExport;
use App\Repositories\CompanyRepository;
use App\Repositories\ReceiptRepository;
use App\Services\Concerns\BaseService;
use App\Models\ReceiptDetail;
use App\Services\Export\Exportable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Rawilk\Printing\Facades\Printing;

class ReceiptService extends BaseService
{
    /**
     * Url prev next page of receipt with agent
     */
    protected $nextPageUrl = null;
    protected $prevPageUrl = null;
    protected $urlPageDetail = null;


    /**
     * @param \App\Repositories\ReceiptRepository $repository
     * @param \App\Repositories\CompanyRepository $companyRepository
     */
    protected $companyRepository;

    public function __construct(ReceiptRepository $repository, CompanyRepository $companyRepository)
    {
        $this->repository = $repository;
        $this->companyRepository = $companyRepository;
    }

    /**
     * Filter user by request
     *
     * @param array $conditions
     *
     * @return mixed
     */
    public function search($conditions = [])
    {
        $this->makeBuilder($conditions);

        $this->builder->with(['agent', 'receiptDetails']);

        // Search receipt by type code
        if ($this->filter->get('type_code')) {
            $this->builder->where('type_code', $this->filter->get('type_code'));
            $this->cleanFilterBuilder('type_code');
        }

        // Search receipt by transaction_year_month
        if ($this->filter->get('transaction_year_month')) {
            $transactionDate = Carbon::parse($this->filter->get('transaction_year_month'));
            $this->builder
                ->whereYear('transaction_date', '=', $transactionDate->format('Y'))
                ->whereMonth('transaction_date', '=', $transactionDate->format('m'));
            $this->cleanFilterBuilder('transaction_year_month');
        }

        //Search receipt by agent_id
        if ($this->filter->get('agent_id')) {
            $this->builder->where('agent_id', $this->filter->get('agent_id'));
            $this->cleanFilterBuilder('agent_id');
        }


        // Search receipt by billing_agent_id
        if ($this->filter->get('billing_agent_id')) {
            $this->builder->whereHas('agent', function (Builder $q) {
                return $q->where('billing_agent_id', $this->filter->get('billing_agent_id'));
            });
            $this->cleanFilterBuilder('billing_agent_id');
        }

        //Search receipt by or_agent_id
        if ($this->filter->get('or_agent_id')) {
            $this->builder->orWhere('agent_id', $this->filter->get('or_agent_id'));
            $this->cleanFilterBuilder('or_agent_id');
        }

        //Search receipt by code agent
        if ($this->filter->get('agent_code')) {
            $this->builder->whereHas('agent', function (Builder $q) {
                return $q->where('code', 'LIKE', '%' . $this->filter->get('agent_code') . '%');
            });
            $this->cleanFilterBuilder('agent_code');
        }

        //Search receipt by name agent
        if ($this->filter->get('agent_name')) {
            $this->builder->whereHas('agent', function (Builder $q) {
                return $q->where('name', 'LIKE', '%' . $this->filter->get('agent_name') . '%');
            });
            $this->cleanFilterBuilder('agent_name');
        }

        //Search receipt by product name
        if ($this->filter->get('product_name')) {
            $this->builder->whereHas('products', function (Builder $q) {
                return $q->where('products.name', 'LIKE', '%' . $this->filter->get('product_name') . '%');
            });
            $this->cleanFilterBuilder('product_name');
        }

        //Search receipt by name agent
        if ($this->filter->get('product_code')) {
            $this->builder->whereHas('products', function (Builder $q) {
                return $q->where('products.code', $this->filter->get('product_code'));
            });
            $this->cleanFilterBuilder('product_code');
        }

        //Search receipt by product name
        if ($this->filter->get('product_name')) {
            $this->builder->whereHas('products', function (Builder $q) {
                return $q->where('products.name', 'LIKE', '%' . $this->filter->get('product_name') . '%');
            });
            $this->cleanFilterBuilder('product_name');
        }

        //Search receipt by start transaction_date
        if ($this->filter->get('transaction_start_date')) {
            $transactionDate = Carbon::parse($this->filter->get('transaction_start_date'));
            $this->builder->where('transaction_date', '>=', $transactionDate);
            $this->cleanFilterBuilder('transaction_start_date');
        }

        //Search receipt by end transaction_date
        if ($this->filter->get('transaction_end_date')) {
            $transactionDate = Carbon::parse($this->filter->get('transaction_end_date'));
            $this->builder->where('transaction_date', '<=', $transactionDate);
            $this->cleanFilterBuilder('transaction_end_date');
        }

        //Search receipt by product name
        if ($this->filter->get('code')) {
            $this->builder->where('code', $this->filter->get('code'));
            $this->cleanFilterBuilder('code');
        }

        //Order by transaction_date
        $sort = $this->filter->get('sort_transaction_date') == 'ASC' ? 'ASC' : 'DESC';
        if ($this->filter->has('sort_transaction_date')) {
            $this->cleanFilterBuilder('sort_transaction_date');
        }
        $this->builder->orderBy('transaction_date', $sort);
        return $this->endFilter();
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
        $record = $this->repository->with(['agent', 'receiptDetails.unit'])
            ->where('type_code', $request->input('type_code', Type::Common))
            ->find($id);

        abort_if(is_null($record), 404);
        return $record;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(Request $request)
    {
        $id = $request->input('id');
        //data receipt
        $receipt = $request->toArray();
        //data product
        $products = $request->input('receipt_details');

        if (!is_null($this->checkUniqueCodeUpdate($request)) && $id) {
            return false;
        }

        DB::beginTransaction();
        try {
            if (!$request->input('code')) {
                $receipt['code'] = $this->repository->plusCodeOne();
            }
            if (!$id) {
                //Create receipt
                $receipt = $this->repository->create($receipt);
                //Create receipt products
                foreach ($products as $key => $product) {
                    unset($products[$key]['receipt_id']);
                }
                $receipt->products()->attach($products);
                $this->withSuccess(trans('receipt.created'));
            } else {
                $record = $this->repository->find($id);
                $receipt['print_status'] = PrintStatus::PRINT;
                //update receipt
                $receipt = $this->repository->updateByModel($record, $receipt);
                UpdateProductReceiptEvent::dispatch($request->all());
                $this->withSuccess(trans('receipt.updated'));
            }

            ProductAgentEvent::dispatch($request->all());
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
        return $receipt;
    }

    /**
     * Delete receipt
     * @param id $id
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @return true or false
     */
    public function delete($id)
    {
        $receipt = $this->repository->find($id);
        $receipt->products()->detach();
        $result = $receipt->delete();

        $this->withSuccess(trans('receipt.deleted'));

        return $result;
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
        $receiptList = $this->repository->with(['agent'])
            ->where('type_code', Type::Common)
            ->orderBy('transaction_date', 'DESC')
            //id desc
            ->orderBy('id', 'DESC');

        $receiptList = $request->input('ids') == 'all' ?
            $receiptList->get() :
            $receiptList->whereIn('id', $request->input('ids'))
            ->orderBy('transaction_date', 'DESC')
            //id desc
            ->orderBy('id', 'DESC')
            ->get();


        $date = Carbon::now()->format('YmdHis');

        $path = SAVE_PATH_FILE_CSV_RECEIPT . FILENAME_RECEIPT . $date . '.csv';

        Excel::store(new ReceiptListExport($receiptList), $path);

        $url = Storage::url($path);

        return url($url);
    }

    /**
     * Export list csv
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string $url
     */
    public function exportCsv(Request $request)
    {
        $receipt = $this->find($request->input('id'), ['agent', 'receiptDetails']);

        $date = Carbon::now()->format('YmdHis');
        $path = SAVE_PATH_FILE_CSV_RECEIPT . FILENAME_RECEIPT . $date . '.csv';
        Excel::store(new ReceiptCsvExport($receipt), $path);
        $url = Storage::url($path);

        return url($url);
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
        $receiptMarutoList = $this->repository->with(['agent'])
            ->where('type_code', Type::ChainStore)
            ->orderBy('transaction_date', 'DESC');

        $receiptMarutoList = $request->input('ids') == 'all' ?
            $receiptMarutoList->get() :
            $receiptMarutoList
            ->whereIn('id', $request->input('ids'))
            //transaction_date desc
            ->orderBy('transaction_date', 'DESC')
            //id desc
            ->orderBy('id', 'DESC')
            ->get();

        $date = Carbon::now()->format('YmdHis');

        $path = SAVE_PATH_FILE_CSV_RECEIPT_MARUTO . FILENAME_RECEIPT_MARUTO . $date . '.csv';

        Excel::store(new ReceiptMarutoListExport($receiptMarutoList), $path);

        $url = Storage::url($path);

        return url($url);
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
        $receiptMarutoList = $this->find($request->input('id'), ['agent']);

        $date = Carbon::now()->format('YmdHis');
        $path = SAVE_PATH_FILE_CSV_RECEIPT_MARUTO . FILENAME_RECEIPT . $date . '.csv';
        Excel::store(new ReceiptMarutoDetailExport($receiptMarutoList), $path);
        $url = Storage::url($path);

        return url($url);
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
        $files = [];

        $receipt = $this->find($request->input('id'), ['agent', 'receiptDetails.unit']);
        $company = $this->companyRepository->first();

        $date = Carbon::now()->format('YmdHis');
        $fileName = FILENAME_RECEIPT . $date . '.pdf';
        $paymentRequest = FILENAME_RECEIPT_PAYMENT_REQUEST . $date . '.pdf';

        $assign = [
            'company' => $company,
            'receipt' => $receipt,
        ];

        $url = (new PrintReceiptExport($assign))->changePath(SAVE_PATH_FILE_PRINT_RECEIPT)->savePdf($fileName);
        $urlPayment = (new PrintPaymentRequestReceiptExport($assign))->changePath(SAVE_PATH_FILE_PRINT_RECEIPT)->savePdf($paymentRequest);

        //Set up file with print page
        $numberPage = $receipt->agent->print_type == PrintType::SET_OF_4->value ? FORE_FILE_PAGE : TWO_FILE_PAGE;

        for ($i = 0; $i < $numberPage; $i++) {
            array_push($files, url($url));
            array_push($files, url($urlPayment));
        }

        return $files;
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
        $receipt = $this->find($request->input('id'));

        return $this->repository->updateByModel($receipt, ['print_status' =>  PrintStatus::PRINTED]);
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
        $urls = [];
        $receipt = $this->find($request->input('id'), ['agent', 'receiptDetails.unit']);
        $company = $this->companyRepository->first();
        $receiptDetailsOfPage = $receipt->receiptDetails->chunk(12);

        foreach ($receiptDetailsOfPage as $key => $receiptDetails) {
            $date = Carbon::now()->format('YmdHis');
            $fileName = FILENAME_RECEIPT_MARUTO . '_' . $company->name . '_' . $date . $key . '.pdf';

            $totalReceiptAmount = 0;
            foreach ($receiptDetails as $receiptDetail) {
                $totalReceiptAmount += $receiptDetail->amount;
            }

            $consumptionTax = $totalReceiptAmount * $receipt->tax / 100;
            $consumptionTax = $receipt->roundingAmount($consumptionTax, $receipt->tax_fraction_rounding_code);
            $assign = [
                'numberPage' => ++$key,
                'company' => $company,
                'receipt' => $receipt,
                'receiptDetails' => $receiptDetails,
                'consumptionTax' => $consumptionTax,
                'totalReceiptAmount' => $totalReceiptAmount,
            ];

            $config = [
                'margin_left'   => 5,
                'margin_right'  => 5,
                'margin_top'    => 8,
                'margin_bottom' => 0,
            ];

            $url = (new PrintReceiptMarutoExport($assign))->setConfig($config)->changePath(SAVE_PATH_FILE_PRINT_RECEIPT_MARUTO)->savePdf($fileName);

            array_push($urls, url($url));
        }

        return $urls;
    }
    /*
     * Search ajax receipt
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string $result
     */
    public function searchAjax($conditions = [])
    {
        $this->makeBuilder($conditions);

        $this->builder->with(['agent', 'receiptDetails.unit']);

        // Search receipt by type code
        if ($this->filter->has('type_code')) {
            $this->builder->where('type_code', $this->filter->get('type_code'));
            $this->cleanFilterBuilder('type_code');
        }

        // Search receipt by type code
        if ($this->filter->has('code')) {
            $this->builder->where('code', $this->filter->get('code'));
            $this->cleanFilterBuilder('code');
        }

        $receipt = $this->builder->first();
        if ($receipt) {
            $receipt['transaction_date'] = $receipt->transaction_date_fm;
            $receipt['price_total_product'] = $receipt->price_total_product;
            $receipt['products'] = $receipt->receiptDetails->toArray();
            $receipt['consumption_tax'] = $receipt->consumption_tax;
            $receipt['total_receipt_amount'] = $receipt->total_receipt_amount;
        }

        return $receipt;
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
        $result = [];
        $receiptIds = [];
        $receipt = $this->repository->find($request->input('id'));
        $typeCode = $receipt->type_code;
        $created = $receipt->created_at;
        $agentId = $receipt->agent_id;
        $url = $typeCode == Type::Common ? RECEIPT_DETAIL_ROUTE : RECEIPT_MARUTO_DETAIL_ROUTE;
        $receiptQuery = $this->repository->orderBy('transaction_date', 'ASC');
        switch ($request->input('prev_next_page')) {
            case 'unchecked':
                $receiptIds = $receiptQuery->where('type_code', $typeCode)
                    ->where('created_at', '<=', $created)
                    ->orWhere('type_code', $typeCode)
                    ->where('created_at', '>=', $created)->pluck('id')->toArray();
                break;
            case 'checked':
                $receiptIds = $receiptQuery->where('type_code', $typeCode)
                    ->where('created_at', '<=', $created)
                    ->where('agent_id', $agentId)
                    ->orWhere('type_code', $typeCode)
                    ->where('agent_id', $agentId)
                    ->where('created_at', '>=', $created)->pluck('id')->toArray();
                break;
            default:
                $result['prevPageUrl'] = null;
                $result['nextPageUrl'] = null;
                break;
        }

        $key = array_search($receipt->id, $receiptIds);
        $result['prevPageUrl'] = array_key_exists($key - 1, $receiptIds) ? route($url, $receiptIds[$key - 1]) : null;
        $result['nextPageUrl'] = array_key_exists($key + 1, $receiptIds) ? route($url, $receiptIds[$key + 1]) : null;
        return $result;
    }

    /**
     * Print n335 receipt
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string $url
     */
    public function printN335Receipt(Request $request)
    {
        $urls = [];
        $receipt = $this->find($request->input('id'), ['agent', 'receiptDetails.unit']);
        $company = $this->companyRepository->first();
        $receiptDetailsOfPage = $receipt->receiptDetails->chunk(12);

        foreach ($receiptDetailsOfPage as $key => $receiptDetails) {
            $date = Carbon::now()->format('YmdHis');
            $fileName = FILENAME_RECEIPT . '_' . $receipt->agent->name . '_' . $date . $key . '.pdf';
            $assign = [
                'company' => $company,
                'receipt' => $receipt,
                'receiptDetails' => $receiptDetails
            ];

            $config = [
                'margin_left'   => 5,
                'margin_right'  => 5,
                'margin_top'    => 8,
                'margin_bottom' => 0,
            ];

            $url = (new PrintN335ReceiptExport($assign))->setConfig($config)->changePath(SAVE_PATH_FILE_PRINT_N335_RECEIPT)->savePdf($fileName);

            array_push($urls, url($url));
        }

        return $urls;
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
        $ids = $request->input('ids');
        $receipts = $this->repository->where('type_code', $request->get('type_code'));
        $receipts = $ids == 'all' ? $receipts->get() : $receipts->whereIn('id', $ids)->get();

        foreach ($receipts as $receipt) {
            $receipt->products()->detach();
            $result = $receipt->delete();
        }

        $this->withSuccess(trans('receipt.deleted'));

        return $result;
    }

    /**
     * Get incremental code
     */
    public function getIncrementalCode($typeCode)
    {
        return $this->repository->getIncrementalCode($typeCode);
    }

    /**
     * Get transaction date default
     */
    public function getTransactionDateDefault($typeCode)
    {
        $receipt = $this->repository->where('type_code', $typeCode)->orderBy('id', 'DESC')->first();
        return !is_null($receipt) ? Carbon::parse($receipt->transaction_date)->format('Y/m/d') : Carbon::now()->format('Y/m/d');
    }

    /**
     * Check unique code
     * @param \Illuminate\Http\Request $request
     */
    private function checkUniqueCodeUpdate(Request $request)
    {
        $agent = $this->repository
            ->where('id', '!=', $request->input('id'))
            ->where('type_code', $request->input('type_code'))
            ->where('code', $request->input('code'))->first();

        return $agent;
    }

    //
    public function listByBillingAgentYearMonth($conditions = [])
    {
        $this->makeBuilder($conditions);

        $this->builder->with(['agent']);

        // Search receipt by transaction_year_month
        if ($this->filter->get('transaction_year_month') && $this->filter->get('billing_agent_id')) {
            $transactionDate = Carbon::parse($this->filter->get('transaction_year_month'));
            $this->builder
                ->whereYear('transaction_date', '=', $transactionDate->format('Y'))
                ->whereMonth('transaction_date', '=', $transactionDate->format('m'));
            $this->cleanFilterBuilder('transaction_year_month');

            // Search receipt by billing_agent_id
            $this->builder->whereHas('agent', function (Builder $q) {
                return $q->where('billing_agent_id', $this->filter->get('billing_agent_id'));
            });
            $this->cleanFilterBuilder('billing_agent_id');
        }

        //Search receipt by or_agent_id
        if ($this->filter->get('or_agent_id') && $this->filter->get('transaction_year_month')) {
            $this->builder->orWhere('agent_id', $this->filter->get('or_agent_id'));
            $this->cleanFilterBuilder('or_agent_id');

            // Search receipt by transaction_year_month
            $transactionDate = Carbon::parse($this->filter->get('transaction_year_month'));
            $this->builder
                ->whereYear('transaction_date', '=', $transactionDate->format('Y'))
                ->whereMonth('transaction_date', '=', $transactionDate->format('m'));
            $this->cleanFilterBuilder('transaction_year_month');
        }

        $this->builder->orderBy('transaction_date', "DESC");

        return $this->endFilter();
    }
}
