<?php

namespace App\Http\Controllers;

use App\Services\RevenueService;
use App\Services\Traits\OptionExport;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Revenue extends Controller
{
    use OptionExport;

    /**
     * @var \App\Services\RevenueService
     */
    protected $revenueService;

    /**
     * @param \App\Services\RevenueService $revenueService
     */
    public function __construct(RevenueService $revenueService)
    {
        $this->revenueService = $revenueService;
    }

    /**
     * Return view list revenue agent
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getAgentRevenueList(Request $request)
    {
        $currentMonth = Carbon::now()->format('Y/m');

        $revenueAgent = $this->revenueService->getAgentRevenueList($request);

        $this->getDataPrint($request->toArray(), ['url' => route(REVENUE_AGENT_PRINT_ROUTE), 'btn' => 'revenue-agent-print']);
        $this->getDataPreview($request->toArray(), ['url' => route(REVENUE_AGENT_PREVIEW_ROUTE), 'btn' => 'revenue-agent-preview']);
        $this->getDataExportCsv($request->toArray(), ['url' => route(REVENUE_AGENT_EXPORT_CSV_ROUTE), 'btn' => 'revenue-agent-export-csv']);
        $dataOptions = $this->getDataOptionExport();

        $assign = [
            'currentMonth' => $currentMonth,
            'revenueAgent' => $revenueAgent ? $revenueAgent['dayData'] : $revenueAgent,
            'total_amount' => $revenueAgent ? $revenueAgent['total_amount'] : null,
            'total_receipt' => $revenueAgent ? $revenueAgent['total_receipt'] : null,
            'dataOptions' => $dataOptions,
        ];

        return view('revenue.agent', $assign);
    }

    /**
     * Return view list revenue product
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getProductRevenueList(Request $request)
    {
        //Get data revenue product
        $revenueProducts = $this->revenueService->getProductRevenueList($request);

        $data = $request->toArray();

        //Get data options export
        $this->getDataPrint($request->toArray(), ['url' => route(REVENUE_PRODUCT_PRINT_ROUTE), 'btn' => 'revenue-product-print']);
        $this->getDataPreview($data, ['url' => route(REVENUE_PRODUCT_PREVIEW_ROUTE), 'btn' => 'revenue-product-preview']);
        $this->getDataExportCsv($data, ['url' => route(REVENUE_PRODUCT_EXPORT_CSV_ROUTE), 'btn' => 'revenue-product-export-csv']);
        $dataOptions = $this->getDataOptionExport();

        $assign = [
            'firstMonth' => Carbon::now()->startOfMonth()->format('Y/m/d'),
            'lastMonth' => Carbon::now()->endOfMonth()->format('Y/m/d'),
            'productRevenue' => $revenueProducts,
            'dataOptions' => $dataOptions,
        ];
        return view('revenue.product', $assign);
    }

    /**
     * Preview revenue agent
     *
     * @param \Illuminate\Http\Request $request
     * @return $url file pdf
     */
    public function previewAgentRevenue(Request $request)
    {
        app()->setLocale('ja');
        $url = $this->revenueService->previewAgentRevenue($request);

        return response()->json(['result' => $url], 200);
    }

    /**
     * Export csv revenue agent
     *
     * @param \Illuminate\Http\Request $request
     * @return $url file pdf
     */
    public function exportCsvAgentRevenue(Request $request)
    {
        app()->setLocale('ja');
        $url = $this->revenueService->exportCsvAgentRevenue($request);

        return response()->json(['result' => $url], 200);
    }

    /**
     * Preview revenue agent
     *
     * @param \Illuminate\Http\Request $request
     * @return $url file pdf
     */
    public function previewProductRevenue(Request $request)
    {
        $url = $this->revenueService->previewProductRevenue($request);

        return response()->json(['result' => $url], 200);
    }

    /**
     * Export csv revenue agent
     *
     * @param \Illuminate\Http\Request $request
     * @return $url file pdf
     */
    public function exportCsvProductRevenue(Request $request)
    {
        app()->setLocale('ja');
        $url = $this->revenueService->exportCsvProductRevenue($request);

        return response()->json(['result' => $url], 200);
    }

    /**
     * Print Agent Revenue
     * @param \Illuminate\Http\Request $request
     * @return $url file pdf
    */
    public function printAgentRevenue(Request $request)
    {
        app()->setLocale('ja');
        $url = $this->revenueService->printAgentRevenue($request);

        return response()->json(['result' => $url], 200);
    }

    /**
     * Print Product Revenue
     * @param \Illuminate\Http\Request $request
     * @return $url file pdf
    */
    public function printProductRevenue(Request $request)
    {
        app()->setLocale('ja');
        $url = $this->revenueService->printProductRevenue($request);

        return response()->json(['result' => $url], 200);
    }
}
