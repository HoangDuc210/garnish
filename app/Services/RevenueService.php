<?php

namespace App\Services;

use App\Exports\RevenueAgentCsvExport;
use App\Exports\RevenueAgentPreviewExport;
use App\Exports\RevenueAgentPrint;
use App\Exports\RevenueAgentPrintExport;
use App\Exports\RevenueProductCsvExport;
use App\Exports\RevenueProductPrintExport;
use App\Exports\RevenueProductReviewExport;
use App\Helpers\Facades\Util;
use App\Repositories\AgentRepository;
use App\Repositories\ReceiptRepository;
use App\Services\Concerns\BaseService;
use App\Services\Traits\OptionExport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class RevenueService extends BaseService
{
    use OptionExport;
    /**
     * @param \App\Repositories\ReceiptRepository $repository
     */
    public function __construct(ReceiptRepository $repository, AgentRepository $agentRepository)
    {
        $this->repository = $repository;
        $this->agentRepository = $agentRepository;
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

        $this->builder->with(['agent', 'receiptDetails.product', 'receiptDetails.unit']);

        //Search receipt by code agent
        if ($this->filter->get('agent_id')) {
            $this->builder->whereHas('agent', function (Builder $q) {
                return $q->where('id', $this->filter->get('agent_id'));
            });
            $this->cleanFilterBuilder('agent_id');
        }

        //Search receipt by month and year
        if ($this->filter->get('month') && $this->filter->get('year')) {
            $this->builder->whereMonth('transaction_date', $this->filter->get('month'));
            $this->builder->whereYear('transaction_date', $this->filter->get('year'));

            $this->cleanFilterBuilder('month');
            $this->cleanFilterBuilder('year');
        }

        //Search receipt by date
        if ($this->filter->get('month_start')) {
            $this->builder->where('transaction_date', '>=', $this->filter->get('month_start'));

            $this->cleanFilterBuilder('month_start');
        }

        //Search receipt by date
        if ($this->filter->get('month_end')) {
            $this->builder->where('transaction_date', '<=', $this->filter->get('month_end'));

            $this->cleanFilterBuilder('month_end');
        }

        return $this->builder->get();
    }

    /**
     * Get list revenue agent
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function getAgentRevenueList(Request $request)
    {
        if (!$request->input('month')) {
            return [];
        }

        $transactionDate = Carbon::createFromFormat('Y/m', $request->input('month'))->endOfMonth();

        $conditions = [
            'month' => $transactionDate->format('m'),
            'year' => $transactionDate->format('Y'),
            'agent_id' => $request->input('agent.id'),
        ];

        $results = $request->input('month') ? $this->search($conditions) : [];

        return $this->getDayData($results, $request);
    }

    /**
     * Get data revenue agent of receipt
     *
     * @return array
     */
    public function getDayData($results, $request)
    {
        $dayData = [];
        $totalAmount = 0;
        $totalReceipt = 0;

        $lastDayOfMonth = Carbon::createFromFormat('Y/m', $request->input('month'))->endOfMonth()->format('d');

        //Set day
        for ($i = 0; $i < $lastDayOfMonth; $i++) {
            $data['receipt'] = [];
            array_push($dayData, $data);
        }

        //Set data day
        foreach ($results as $result) {
            array_push($dayData[$result->formatted_transaction_date_with_day - 1]['receipt'], $result->total_receipt_amount);
            $totalAmount += $result->total_receipt_amount;
        }

        //Calculate amount of revenue
        $totalReceipt = $results->count();

        $assign = [
            'dayData' => $dayData,
            'total_amount' => $totalAmount,
            'total_receipt' => $totalReceipt,
        ];

        return $assign;
    }

    /**
     * Get list revenue product
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function getProductRevenueList(Request $request)
    {

        if (!$request->input('month_start') || !$request->input('month_end') || !$request->input('agent.id')) {
            return [];
        }

        $conditions = [
            'month_start' => $request->input('month_start'),
            'month_end' => $request->input('month_end'),
            'agent_id' => $request->input('agent.id'),
        ];

        $results = $this->search($conditions);

        return $this->getProductData($results);
    }

    /**
     * Get data product revenue of receipt
     *
     * @return []
     */
    public function getProductData($results)
    {
        $dataProduct = [];

        foreach ($results as $result) {
            foreach ($result->receiptDetails as $receiptDetail) {
                $data = [
                    'name' => $receiptDetail->name,
                    'quantity' => $receiptDetail->quantity,
                    'unit' => $receiptDetail->unit->name,
                    'amount' => $receiptDetail->amount,
                    'price' => number_format($receiptDetail->price),
                ];
                array_push($dataProduct, $data);
            }
        }

        $products = [];
        foreach ($dataProduct as $product) {
            $data = [];
            $associated = [
                'name' => $product['name'],
                'unit' => $product['unit'],
                'price' => $product['price']
            ];
            foreach ($dataProduct as $pro) {
                if (count(array_intersect_assoc($associated, $pro)) === 3) {
                    array_push($data, $pro);
                }
            }

            array_push($products, json_encode($data));

        }

        $resultPro = [];
        foreach (array_unique($products) as $key => $product) {
            $product = json_decode($product);

            $quantity = [];
            $amount = [];
            foreach ($product as $key => $pro) {
                array_push($quantity, $pro->quantity);
                array_push($amount, $pro->amount);
            }


            $data = [
                'name' => $product[0]->name,
                'quantity' => array_sum($quantity),
                'unit' => $product[0]->unit,
                'amount' => number_format(array_sum($amount)),
                'price' => $product[0]->price,
            ];
            array_push($resultPro, $data);
        }

        return $resultPro;
    }

    /**
     * Preview revenue agent
     *
     * @param \Illuminate\Http\Request $request
     * @return $url file pdf
     */
    public function previewAgentRevenue(Request $request)
    {
        $agentRevenueList = $this->getAgentRevenueList($request);
        $agent = $this->agentRepository->find($request->input('agent.id'));
        $customer = '['. $agent->code .'] ' . $agent->name;

        $assign = [
            'agentRevenueList' => $agentRevenueList['dayData'],
            'total_amount' => $agentRevenueList['total_amount'],
            'total_receipt' => $agentRevenueList['total_receipt'],
            'agent' => $customer,
            'aggregation_date' => $request->input('month'),
            'created_date' => Carbon::now()->format('Y/m/d H:s:i'),
        ];

        $fileName = '得意先別売上一覧表' . Carbon::now()->format('YmdHsi') . '.pdf';

        $file = (new RevenueAgentPreviewExport($assign))->changePath(SAVE_PATH_FILE_PREVIEW_REVENUE_AGENT)->savePdf($fileName);

        return url($file);
    }

    /**
     * Export csv revenue agent
     *
     * @param \Illuminate\Http\Request $request
     * @return $url file pdf
     */
    public function exportCsvAgentRevenue(Request $request)
    {
        $agentRevenueList = $this->getAgentRevenueList($request);
        $agent = $this->agentRepository->find($request->input('agent.id'));
        $month = str_replace('/', '-', $request->input('month'));
        $assign = [
            'agentRevenueList' => $agentRevenueList['dayData'],
            'total_amount' => $agentRevenueList['total_amount'],
            'total_receipt' => $agentRevenueList['total_receipt'],
            'agent' => $agent->name,
            'aggregation_date' => Carbon::parse($month)->format('Y年m月'),
            'created_date' => Carbon::now()->format('Y/m/d H:s:i'),
        ];

        $date = Carbon::now()->format('YmdHis');
        $path = SAVE_PATH_FILE_CSV_REVENUE_AGENT . '得意先別売上一覧表' . $date .'.csv';
        Excel::store(new RevenueAgentCsvExport($assign), $path);
        $url = Storage::url($path);

        return url($url);
    }

    /**
     * Preview revenue agent
     *
     * @param \Illuminate\Http\Request $request
     * @return $url file pdf
     */
    public function previewProductRevenue(Request $request)
    {
        $productRevenueList = $this->getProductRevenueList($request);
        $agent = $this->agentRepository->find($request->input('agent.id'));
        $customer = '['. $agent->code .'] ' . $agent->name;

        $assign = [
            'productRevenueList' => $productRevenueList,
            'title_preview' => '商品別　売上集計表',
            'agent' => $customer,
            'revenue_date' => $request->input('month_start') . ' ～ ' . $request->input('month_end'),
            'created_date' => Carbon::now()->format('Y/m/d H:s:i'),
        ];

        $fileName = '商品別売上集計表' . Carbon::now()->format('YmdHsi') . '.pdf';

        $file = (new RevenueProductReviewExport($assign))->changePath(SAVE_PATH_FILE_PREVIEW_REVENUE_PRODUCT)->savePdf($fileName);

        return url($file);
    }

    /**
     * Export csv revenue agent
     *
     * @param \Illuminate\Http\Request $request
     * @return $url file pdf
     */
    public function exportCsvProductRevenue(Request $request)
    {
        $productRevenueList = $this->getProductRevenueList($request);
        $agent = $this->agentRepository->find($request->input('agent.id'));
        $customer = '['. $agent->code .'] ' . $agent->name;

        $assign = [
            'productRevenueList' => $productRevenueList,
            'title_preview' => '商品別　売上集計表',
            'agent' => $customer,
            'revenue_date' => $request->input('month_start') . ' ～ ' . $request->input('month_end'),
            'created_date' => Carbon::now()->format('Y/m/d H:s:i'),
        ];

        $date = Carbon::now()->format('YmdHis');
        $path = SAVE_PATH_FILE_CSV_REVENUE_PRODUCT . '商品別売上集計表' . $date .'.csv';
        Excel::store(new RevenueProductCsvExport($assign), $path);
        $url = Storage::url($path);

        return url($url);
    }

    /**
     * Print revenue agent
     *
     * @param \Illuminate\Http\Request $request
     * @return $url file pdf
     */
    public function printAgentRevenue(Request $request)
    {
        $agentRevenueList = $this->getAgentRevenueList($request);
        $agent = $this->agentRepository->find($request->input('agent.id'));
        $customer = '['. $agent->code .'] ' . $agent->name;

        $assign = [
            'agentRevenueList' => $agentRevenueList['dayData'],
            'total_amount' => $agentRevenueList['total_amount'],
            'total_receipt' => $agentRevenueList['total_receipt'],
            'agent' => $customer,
            'aggregation_date' => $request->input('month'),
            'created_date' => Carbon::now()->format('Y/m/d H:s:i'),
        ];

        $fileName = '得意先別売上一覧表' . Carbon::now()->format('YmdHsi') . '.pdf';

        $file = (new RevenueAgentPrintExport($assign))->changePath(SAVE_PATH_FILE_PRINT_REVENUE_AGENT)->savePdf($fileName);

        return url($file);
    }

    /**
     * Print revenue product
     *
     * @param \Illuminate\Http\Request $request
     * @return $url file pdf
     */
    public function printProductRevenue(Request $request)
    {
        $productRevenueList = $this->getProductRevenueList($request);
        $agent = $this->agentRepository->find($request->input('agent.id'));
        $customer = '['. $agent->code .'] ' . $agent->name;

        $assign = [
            'productRevenueList' => $productRevenueList,
            'title_print' => '商品別　売上集計表',
            'agent' => $customer,
            'revenue_date' => $request->input('month_start') . ' ～ ' . $request->input('month_end'),
            'created_date' => Carbon::now()->format('Y/m/d H:s:i'),
        ];

        $fileName = '商品別売上集計表' . Carbon::now()->format('YmdHsi') . '.pdf';

        $file = (new RevenueProductPrintExport($assign))->changePath(SAVE_PATH_FILE_PRINT_REVENUE_PRODUCT)->savePdf($fileName);

        return url($file);
    }
}
