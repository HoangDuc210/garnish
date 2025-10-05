<?php

namespace App\Http\Controllers;

use App\Services\BillingService;
use App\Services\ReceiptService;
use App\Services\AgentService;
use Illuminate\Http\Request;
use App\Http\Requests\Billing\StoreByBillingAgentYearMonthRequest;
use App\Http\Requests\Billing\StoreByYearMonthRequest;
use App\Http\Requests\Billing\ExportCsvByBillingAgentYearMonthRequest;
use App\Http\Requests\Billing\ExportCsvByYearMonthRequest;
use App\Http\Requests\Billing\ExportCsvBillingAgentCollationsRequest;
use App\General\Functions;
use App\Models\Agent;
use App\Models\Company;
use App\Services\CompanyService;
use Carbon\Carbon;

class Billing extends Controller
{
    /**
     * @var \App\Services\BillingService
     */
    protected $billingService;

    /**
     * @var \App\Services\ReceiptService
     */
    protected $receiptService;

    /**
     * @var \App\Services\AgentService
     */
    protected $agentService;

    /**
     * @param \App\Services\CompanyService
     */
    protected $companyService;

    public function __construct(
        BillingService $billingService,
        ReceiptService $receiptService,
        AgentService $agentService,
        CompanyService $companyService
        )
    {
        $this->billingService = $billingService;
        $this->receiptService = $receiptService;
        $this->agentService = $agentService;
        $this->companyService = $companyService;
    }

    /**
     * Search
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $params =  Functions::makeQueryStringParameters($request->all());
        $data = $this->billingService->search($params + [
            'all' => true,
            'order_by' => 'billing_agent_id',
            'order_direction' => 'asc'
        ]);

        return response()->json([
            'result' => true,
            'data' => $data,
        ]);
    }

    /**
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function listByBillingAgentYearMonth(Request $request)
    {
        $yearMonthOptions = Functions::makeYearMonthOptions();

        $defaultYearMonth = array_key_first($yearMonthOptions);

        $billingAgentId = $request->billing_agent_id ? $request->billing_agent_id : 0;

        $yearMonth = $request->calculate_date ? $request->calculate_date : $defaultYearMonth;

        $billingAgent =  $billingAgentId ? $this->agentService->find($billingAgentId) : [];

        $company = $this->companyService->company();

        $billing = $this->billingService->search([
            'all' => true,
            'billing_agent_id' => $billingAgentId,
            'calculate_date' => $yearMonth,
        ])->first();

        $receipts =  $this->billingService->receiptListByBillingAgentYearMonth(
            $billingAgentId,
            Carbon::parse($yearMonth),
        );

        $billingReceipts = $this->billingService->getBillingReceipts($receipts, $billing);

        //Last record of array $billingReceipts for show total deposit_amount
        $lastRecord = end($billingReceipts);

        $lastBilledAmount = $this->billingService->lastBilledAmount($billingAgentId, $yearMonth);

        $assign = [
            'company' => $company,
            'billingAgent' => $billingAgent,
            'billing' => $billing,
            'billingReceipts' => $billingReceipts,
            'yearMonthOptions' => $yearMonthOptions,
            'defaultYearMonth' => $defaultYearMonth,
            'lastBilledAmount' => $lastBilledAmount,
            'lastRecord' => $lastRecord,
        ];

        return view('billing.listByBillingAgentYearMonth', $assign);
    }

    /**
     * @param \App\Http\Requests\Billing\StoreByBillingAgentYearMonthRequest $request
     */
    public function storeByBillingAgentYearMonth(StoreByBillingAgentYearMonthRequest $request)
    {
        $this->billingService->storeByBillingAgentYearMonth($request);

        return redirect()->route(
            BILLING_LIST_BY_BILLING_AGENT_YEAR_MONTH_ROUTE,
            [
                'calculate_date' => $request->input('calculate_date'),
                'billing_agent_id' => $request->input('billing_agent_id'),
            ]
        )->withInput();
    }

    /**
     * @param \App\Http\Requests\Billing\ExportCsvByBillingAgentYearMonthRequest $request
     */
    public function exportCsvByBillingAgentYearMonth(ExportCsvByBillingAgentYearMonthRequest $request)
    {
        $url = $this->billingService->exportCsvByBillingAgentYearMonth($request);

        return response()->json([
            'result' => true,
            'data' => $url
        ], 200);
    }

    /**
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function listByYearMonth(Request $request)
    {
        $yearMonthOptions = Functions::makeYearMonthOptions();
        $defaultYearMonth = array_key_first($yearMonthOptions);
        $yearMonth = $request->calculate_date ? $request->calculate_date : '';
        $billings = $this->billingService->search([
            'all' => true,
            'calculate_date' => $yearMonth,
        ]);

        $billings = $billings->sort(function ($a, $b) {
            if ($a->billingAgent?->code < $b->billingAgent?->code) {
                return -1;
            }
            if ($a->billingAgent?->code > $b->billingAgent?->code) {
                return 1;
            }
            return 0;
        })->values();

        $totalBillings = [
            'last_billed_amount' => $billings->sum('last_billed_amount'),
            'deposit_amount' => $billings->sum('deposit_amount'),
            'carried_forward_amount' => $billings->sum('carried_forward_amount'),
            'final_receipt_amount' => $billings->sum('final_receipt_amount'),
            'tax_amount' => $billings->sum('tax_amount'),
            'billing_amount' => $billings->sum('billing_amount'),
        ];

        $assign = [
            'billings' => $billings,
            'totalBillings' => $totalBillings,
            'yearMonthOptions' => $yearMonthOptions,
            'createdDate'=> Carbon::now()->format('Y/m/d H:i:s'),
            'defaultYearMonth' => Carbon::parse($request->input('calculate_date'))->format('Y/m'),
        ];

        return view('billing.listByYearMonth', $assign);
    }

    /**
     * @param \App\Http\Requests\Billing\StoreByYearMonthRequest $request
     */
    public function storeByYearMonth(StoreByYearMonthRequest $request)
    {
        $this->billingService->storeByYearMonth($request);

        return redirect()->route(
            BILLING_LIST_BY_YEAR_MONTH_ROUTE,
            ['calculate_date' => $request->input('calculate_date')]
        )->withInput();
    }

    /**
     * @param \App\Http\Requests\Billing\ExportCsvByYearMonthRequest $request
     */
    public function exportCsvByYearMonth(ExportCsvByYearMonthRequest $request)
    {
        $url = $this->billingService->exportCsvByYearMonth($request);

        return response()->json([
            'result' => true,
            'data' => $url
        ], 200);
    }

    /**
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function listBillingAgentCollations(Request $request)
    {
        $yearMonthOptions = Functions::makeYearMonthOptions();
        $defaultYearMonth = array_key_first($yearMonthOptions);
        $billingAgentId = $request->billing_agent_id ? $request->billing_agent_id : 0;
        $yearMonth = $request->calculate_date ? $request->calculate_date : $defaultYearMonth;
        $company = Company::first();
        $billingAgent = Agent::find($billingAgentId);
        $collations = $this->billingService->listBillingAgentCollations($billingAgentId, $yearMonth);

        $assign = [
            'company' => $company,
            'billingAgent' => $billingAgent,
            'collations' => $collations,
            'yearMonthOptions' => $yearMonthOptions,
            'defaultYearMonth' => $defaultYearMonth,
        ];

        return view('billing.listBillingAgentCollations', $assign);
    }

    /**
     * @param \App\Http\Requests\Billing\ExportCsvBillingAgentCollationsRequest $request
     */
    public function exportCsvBillingAgentCollations(ExportCsvBillingAgentCollationsRequest $request)
    {
        $url = $this->billingService->exportCsvBillingAgentCollations($request);

        return response()->json([
            'result' => true,
            'data' => $url
        ], 200);
    }

    /**
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function listByBatch(Request $request)
    {
        $yearMonthOptions = Functions::makeYearMonthOptions();
        $defaultYearMonth = array_key_first($yearMonthOptions);
        $yearMonth = $request->calculate_date ? $request->calculate_date : '';
        $company = Company::first();
        $billingAgents = $this->billingService->billingAgentListByYearMonth(Carbon::parse($yearMonth));

        $assign = [
            'company' => $company,
            'billingAgents' => $billingAgents,
            'billings' => $this->billingService->search([
                'all' => true,
                'calculate_date' => $yearMonth,
            ]),
            'yearMonthOptions' => $yearMonthOptions,
            'defaultYearMonth' => $defaultYearMonth,
        ];

        return view('billing.listByBatch', $assign);
    }

    /**
     * @param \App\Http\Requests\Billing\StoreByYearMonthRequest $request
     */
    public function storeByBatch(StoreByYearMonthRequest $request)
    {
        $this->billingService->storeByYearMonth($request);

        return redirect()->route(
            BILLING_LIST_BY_BATCH_ROUTE,
            ['calculate_date' => $request->input('calculate_date')]
        )->withInput();
    }

    /**
     * Get payment last deposit_amount
     * @param \Illuminate\Http\Request $request
     *
     * @return $billing
     */
    public function getDepositAmountOfAgent(Request $request)
    {
        $billing = $this->billingService->getDepositAmountOfAgent($request);

        return response()->json(['result' => $billing], 200);
    }

    /**
     * Print billing agent year month
     * @param \Illuminate\Http\Request $request
     *
     * @return url pdf
     */
    public function printBillingAgentYearMonth(Request $request)
    {
        $pdfs = $this->billingService->printBillingAgentYearMonth($request);

        return response()->json($pdfs, 200);
    }

    /**
     * Print list by year month
     * @param \Illuminate\Http\Request $request
     *
     * @return url pdf
     */
    public function printListByYearMonth(Request $request)
    {
        $pdf = $this->billingService->printListByYearMonth($request);

        return response()->json($pdf, 200);
    }

    /**
     * Print list collations
     * @param \Illuminate\Http\Request $request
     *
     * @return url pdf
     */
    public function printListCollations(Request $request)
    {
        $pdf = $this->billingService->exportListCollations($request);

        return response()->json($pdf, 200);
    }

    /**
     * Preview list collations
     * @param \Illuminate\Http\Request $request
     *
     * @return url pdf
     */
    public function previewListCollations(Request $request)
    {
        $pdf = $this->billingService->exportListCollations($request);

        return response()->json($pdf, 200);
    }

    /**
     * Print list by batch
     * @param \Illuminate\Http\Request $request
     *
     * @return url pdf
     */
    public function printListByBatch(Request $request)
    {
        $pdf = $this->billingService->printListByBatch(['all' => true]);
        return response()->json($pdf, 200);
    }

    /**
     * Preview list by batch
     * @param \Illuminate\Http\Request $request
     *
     * @return url pdf
     */
    public function previewListByBatch()
    {
        $pdf = $this->billingService->printListByBatch(['all' => true]);
        return response()->json($pdf, 200);
    }
}
