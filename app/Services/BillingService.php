<?php

namespace App\Services;

use App\Repositories\BillingRepository;
use App\Services\Concerns\BaseService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Agent;
use App\Models\Receipt;
use App\Models\Deposit;
use App\Exports\Billing\BillingAgentYearMonthExport;
use App\Exports\Billing\YearMonthExport;
use App\Exports\Billing\BillingAgentCollations;
use App\Enums\Deposit\Type as DepositType;
use App\Enums\Agent\FractionRounding as AgentFractionRounding;
use App\Repositories\DepositRepository;
use App\Exports\Billing\PrintBillingAgentYearMonthExport;
use App\Exports\Billing\PrintListByPatch;
use App\Exports\Billing\PrintListByYearMonthExport;
use App\Exports\Billing\PrintListCollations;
use App\Helpers\Facades\MergePdf;
use App\Services\AgentService;

class BillingService extends BaseService
{
    /**
     * @param \App\Repositories\BillingRepository $repository
     * @param \App\Repositories\DepositRepository $depositRepository
     */
    protected $repository;
    protected $depositRepository;

     /**

     * @var \App\Services\AgentService
     * @var \App\Services\CompanyService
     */
    protected $agentService;
    protected $companyService;

    /**
     * @var \App\Services\ReceiptService
     */
    protected $receiptService;

    public function __construct(
        BillingRepository $repository,
        AgentService $agentService,
        ReceiptService $receiptService,
        DepositRepository $depositRepository,
        CompanyService $companyService

    )
    {
        $this->repository = $repository;
        $this->agentService = $agentService;
        $this->receiptService = $receiptService;
        $this->depositRepository = $depositRepository;
        $this->companyService = $companyService;
    }

    /**
     * Filter list by request
     *
     * @param array $conditions
     *
     * @return mixed
     */
    public function search($conditions = [])
    {
        $this->makeBuilder($conditions)->with(['billingAgent']);

        if ($this->filter->has('calculate_date')) {
            $this->builder->where('calculate_date', $this->filter->get('calculate_date'));

            $this->cleanFilterBuilder('calculate_date');
        }

        if ($this->filter->has('billing_agent_id')) {
            $this->builder->where('billing_agent_id', $this->filter->get('billing_agent_id'));

            $this->cleanFilterBuilder('billing_agent_id');
        }

        if ($this->filter->has('in___billing_agent_id')) {
            $this->builder->whereIn('billing_agent_id', $this->filter->get('in___billing_agent_id'));

            $this->cleanFilterBuilder('in___billing_agent_id');
        }

        // order_by
        if ($this->filter->has('order_by')) {
            $direction =
                $this->filter->has('order_direction') ?
                $this->filter->get('order_direction') : 'asc';
            $this->builder->orderBy($this->filter->get('order_by'), $direction);
            $this->cleanFilterBuilder('order_by');
        }

        $result = $this->endFilter();

        foreach ($result as $item) {
            $item['billing_amount'] = $item->billing_amount;
            $item['final_receipt_amount'] = $item->final_receipt_amount;

            $calculateDate = Carbon::parse($item->calculate_date);
            $billingAgent = $item->billingAgent;
            if ($billingAgent) {
                $receipts = Receipt::whereHas('agent', function (Builder $q) use ($billingAgent) {
                    return $q->where('billing_agent_id', $billingAgent->id);
                })
                    ->whereYear('transaction_date', '=', $calculateDate->format('Y'))
                    ->whereMonth('transaction_date', '=', $calculateDate->format('m'))
                    ->get();
                foreach ($receipts as $receipt) {
                    $receipt['deposits'] = $receipt->deposits;
                    $receipt['total_amount'] = $receipt->total_amount;

                    foreach ($receipt['deposits'] as $deposit) {
                        $deposit['type_label'] = $deposit->type_label;
                    }
                }

                $item['receipts'] = $receipts;
                $item['billing_receipts'] = $this->getBillingReceipts($receipts, $item);
            }
        }

        return $result;
    }

    /**
     * receiptListByBillingAgentYearMonth
     *
     * @param int $billingAgentId
     * @param date $yearMonth
     *
     * @return mixed
     */
    public function receiptListByBillingAgentYearMonth($billingAgentId, $yearMonth)
    {
        $conditions = [
            'billing_agent_id' => $billingAgentId,
            'or_agent_id' => $billingAgentId,
            'transaction_year_month' => $yearMonth
        ];
        $receipts = $this->receiptService->search($conditions);
        return $receipts;
    }

    /**
     * roundByCode
     *
     * @param int $amount
     * @param string $code
     *
     * @return int
     */
    public function roundByCode($amount, $code)
    {
        switch ($code) {
            case AgentFractionRounding::FourDownFiveUp:
                return round($amount, 0, PHP_ROUND_HALF_UP);
            case AgentFractionRounding::Truncation:
                return floor($amount);
            case AgentFractionRounding::RoundingUp:
                return ceil($amount);
            default:
                return $amount;
        }
    }

    /**
     * getBillingReceipts
     *
     * @param mixed $receipts
     * @param mixed $billing
     *
     * @return mixed
     */
    public function getBillingReceipts($receipts, $billing)
    {
        $result = [];

        foreach ($receipts as $receipt) {
            $receiptInfo = [
                'is_receipt' => true,
                'is_deposit' => false,
                'is_sub_total' => false,
                'is_total' => false,
                'transaction_date' => $receipt->formatted_transaction_date,
                'id' => $receipt->id,
                'deposit_amount' => "",
                'total_amount' => $receipt->total_amount,
                'memo' => $receipt->memo,
                'code' => $receipt->code,
            ];

            array_push($result, $receiptInfo);
        }

        $calculateDate = Carbon::parse($billing?->calculate_date);
        $deposits = Deposit::where('billing_agent_id', $billing?->billing_agent_id)
            ->whereYear('payment_date', '=', $calculateDate->format('Y'))
            ->whereMonth('payment_date', '=', $calculateDate->format('m'))
            ->orderBy('payment_date', 'asc')
            ->get();
        foreach ($deposits as $deposit) {
            $depositInfo = [
                'is_receipt' => false,
                'is_deposit' => true,
                'is_sub_total' => false,
                'is_total' => false,
                'transaction_date' => $deposit->formatted_payment_date,
                'id' => DepositType::fromValue($deposit->type_code)->description,
                'deposit_amount' => $deposit->amount,
                'total_amount' => "",
                'memo' => $deposit->memo,
                'code' => '【'.DepositType::fromValue($deposit->type_code)->description.'】',
            ];

            array_push($result, $depositInfo);
        }

        $result = collect($result)->sortBy(
            ['transaction_date', 'asc'],
            ['is_receipt', 'asc']
        );
        $result = $result->values()->toArray();

        $receiptInfo = [
            'is_receipt' => false,
            'is_deposit' => false,
            'is_total' => false,
            'is_sub_total' => true,
            'transaction_date' => "",
            'id' => "【御買上計】",
            'deposit_amount' => "",
            'total_amount' => $billing?->receipt_amount,
            'memo' => "",
            'code' =>  "【御買上計】",
        ];
        $collectionInfo = [
            'is_receipt' => false,
            'is_deposit' => false,
            'is_total' => false,
            'is_sub_total' => true,
            'transaction_date' => "",
            'id' => "【帳合料】",
            'deposit_amount' => "",
            'total_amount' => $billing?->collection_amount,
            'memo' => "",
            'code' => "【帳合料】",
        ];
        $taxInfo = [
            'is_receipt' => false,
            'is_deposit' => false,
            'is_total' => false,
            'is_sub_total' => true,
            'transaction_date' => "",
            'id' => "【消費税】",
            'deposit_amount' => "",
            'total_amount' => $billing?->tax_amount,
            'memo' => "",
            'code' => "【消費税】",

        ];

        //Calculate total deposit amount
        $totalDepositAmount = 0;
        foreach ($deposits as $deposit) {
            $totalDepositAmount += $deposit->amount;
        }
        //Total amount
        $totalInfo = [
            'is_receipt' => false,
            'is_deposit' => false,
            'is_sub_total' => false,
            'is_total' => true,
            'transaction_date' => "",
            'id' => "【合計】",
            //'deposit_amount' => $billing?->deposit_amount, //old
            'deposit_amount' => $totalDepositAmount,
            'total_amount' => $billing?->receipt_amount + $billing?->collection_amount + $billing?->tax_amount,
            'memo' => "",
            'code' => "【合計】",
        ];

        array_push($result, $receiptInfo);
        if (abs($billing?->collection_amount) > 0) {
            array_push($result, $collectionInfo);
        }
        array_push($result, $taxInfo);
        array_push($result, $totalInfo);

        return $result;
    }

    /**
     * getBillingReceipts
     *
     * @param mixed $agents
     * @param mixed $receipts
     *
     * @return mixed
     */
    public function getCollations($agents, $receipts)
    {
        $result = [];

        foreach ($agents as $agent) {
            $agentInfo = [
                'is_exception' => true,
                'is_agent_title' => true,
                'is_total' => false,
                'id' => "",
                'transaction_date' => $agent->name,
                'total_amount' => "",
                'memo' => "",
                'code' => ""
            ];
            $agentReceipts = [];
            $agentReceiptAmount = 0;
            foreach ($receipts as $receipt) {
                if ($receipt->agent_id === $agent->id) {
                    array_push($agentReceipts, $receipt);
                    $agentReceiptAmount += $receipt->total_amount;
                }
            }
            $totalInfo = [
                'is_exception' => true,
                'is_agent_title' => false,
                'is_total' => true,
                'id' => "",
                'transaction_date' => "",
                'total_amount' => $agentReceiptAmount,
                'memo' => $agent->name . trans('billing.total_receipts_amount'),
                'code' => ""
            ];

            foreach ($agentReceipts as $receipt) {
                $receipt['is_exception'] = false;
                $receipt['is_agent_title'] = false;
                $receipt['transaction_date'] = is_null($receipt->formatted_transaction_date) ? "" : $receipt->formatted_transaction_date;
                $receipt['is_total'] = false;
                $receipt['total_amount'] = $receipt->total_amount;
            }

            if (count($agentReceipts) > 0) {
                array_push($result, $agentInfo);
                array_push($result, ...$agentReceipts);
                array_push($result, $totalInfo);
            }
        }

        return $result;
    }

    /**
     * billingAgentListYearMonth
     *
     * @param date $yearMonth
     *
     * @return array
     */
    public function billingAgentListByYearMonth($yearMonth)
    {
        $result = [];
        $billingAgents = $this->agentService->getBillingAgents();
        $calculateReceipts = Receipt::whereYear('transaction_date', '=', $yearMonth->format('Y'))
            ->whereMonth('transaction_date', '=', $yearMonth->format('m'))
            ->whereHas('agent', function (Builder $q) use ($billingAgents) {
                return $q->whereIn('billing_agent_id', $billingAgents->pluck('id'));
            })
            ->get();

        foreach ($billingAgents as $billingAgent) {
            $hasReceipts = false;
            foreach ($calculateReceipts as $calculateReceipt) {
                if ($billingAgent->id === $calculateReceipt->billing_agent_id) {
                    $hasReceipts = true;
                }
            }

            if ($hasReceipts) {
                array_push($result, $billingAgent);
            }
        }

        return $result;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function storeByBillingAgentYearMonth(Request $request)
    {
        $billingAgentId = $request->input('billing_agent_id');
        $calculateDate = Carbon::parse($request->input('calculate_date'));

        // check existing billing
        $existingBillings = $this->repository->where('billing_agent_id', $billingAgentId)
            ->whereYear('calculate_date', '=', $calculateDate->format('Y'))
            ->whereMonth('calculate_date', '=', $calculateDate->format('m'))
            ->get();
        foreach ($existingBillings as $existingBilling) {
            $existingBilling->forceDelete();
        }

        $isAgentExists = Agent::where('id', '=', $billingAgentId)->exists();
        if (!$isAgentExists) {
            return $this->withErrors(trans('billing.not_exists'));
        }

        $data = $this->makeStoreData($billingAgentId, $calculateDate);
        $result = $this->repository->create($data);

        return $result;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function storeByYearMonth(Request $request)
    {
        $calculateDate = Carbon::parse($request->input('calculate_date'));
        $billingAgentIds = $this->agentService->getBillingAgents()->pluck('id');

        foreach ($billingAgentIds as $billingAgentId) {
            // check existing billing
            $existingBillings = $this->repository->where('billing_agent_id', $billingAgentId)
                ->whereYear('calculate_date', '=', $calculateDate->format('Y'))
                ->whereMonth('calculate_date', '=', $calculateDate->format('m'))
                ->get();
            foreach ($existingBillings as $existingBilling) {
                $existingBilling->forceDelete();
            }

            $data = $this->makeStoreData($billingAgentId, $calculateDate);
            $this->repository->create($data);
        }

        $calculateAgentList =  $this->billingAgentListByYearMonth($calculateDate);
        if (!$calculateAgentList) {
            $this->withErrors(trans('billing.no_billings'));
        }
    }

    /**
     * make store data
     *
     * @param int $billingAgentId
     * @param date $calculateDate
     *
     * @return mixed
     */
    public function makeStoreData($billingAgentId, $calculateDate)
    {
        $lastCalculatedDate = $calculateDate->copy()->subMonths(1);

        // get billing agent
        $billingAgent = Agent::where('id', $billingAgentId)->first();

        // calculate deposit amount
        $deposits = Deposit::where('billing_agent_id', $billingAgentId)
            ->whereYear('payment_date', '=', $calculateDate->format('Y'))
            ->whereMonth('payment_date', '=', $calculateDate->format('m'))
            ->get();
        $depositAmount = $deposits->sum('amount');
        $depositAmount = $this->roundByCode($depositAmount, $billingAgent->fraction_rounding_code);

        // calculate last billed
        $lastBillings = $this->repository->where('billing_agent_id', $billingAgentId)
            ->where('calculate_date', '<=', $lastCalculatedDate->format('Y/m/d'))
            ->get();
        $lastBilledAmount = $lastBillings->sum('billing_amount');
        $lastBilledAmount = $this->roundByCode($lastBilledAmount, $billingAgent->fraction_rounding_code);

        // calculate receipt amount
        $receipts = $this->receiptListByBillingAgentYearMonth($billingAgentId, $calculateDate);
        $receiptAmount = $receipts->sum('total_amount');
        $receiptAmount = $this->roundByCode($receiptAmount, $billingAgent->fraction_rounding_code);

        // calculate collection amount
        $collectionAmount = ($receiptAmount * $billingAgent->collection_rate) / 100;
        $collectionAmount = $this->roundByCode($collectionAmount, $billingAgent->fraction_rounding_code);
        $collectionAmount = $collectionAmount ? -$collectionAmount : 0;

        // calculate tax amount
        $taxAmount = 0;
        foreach ($receipts as $receipt) {
            $receiptCollectionAmount = ($receipt->total_amount * $billingAgent->collection_rate) / 100;
            $taxAmount += (($receipt->total_amount - $receiptCollectionAmount) * $receipt->tax) / 100;
        }
        $taxAmount = $taxAmount;
        $taxAmount = $this->roundByCode($taxAmount, $billingAgent->fraction_rounding_code);

        // calculate carried forward amount
        $carriedForwardAmount = $lastBilledAmount - $depositAmount;
        $carriedForwardAmount = $this->roundByCode($carriedForwardAmount, $billingAgent->fraction_rounding_code);

        $data = [
            'billing_agent_id' => $billingAgentId,
            'calculate_date' => $calculateDate->format('Y/m/d'),
            'last_billed_amount' => $lastBilledAmount,
            'deposit_amount' => $depositAmount,
            'receipt_amount' => $receiptAmount,
            'collection_amount' => $collectionAmount,
            'tax_amount' => $taxAmount,
            'carried_forward_amount' => $carriedForwardAmount,
        ];

        return $data;
    }

    /**
     * list billing agent collation
     *
     * @param int $billingAgentId
     * @param string $calculateDate
     *
     * @return mixed
     */
    public function listBillingAgentCollations($billingAgentId, $calculateDate)
    {
        $calculateDate = Carbon::parse($calculateDate);
        $agents = Agent::where('billing_agent_id', $billingAgentId)->get();
        $receipts = $this->receiptListByBillingAgentYearMonth($billingAgentId, $calculateDate);
        $collations = $this->getCollations($agents, $receipts);

        return $collations;
    }

    /**
     * Export csv
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function exportCsvByBillingAgentYearMonth($request)
    {
        $billingAgentId = $request->input('billing_agent_id');
        $calculateDate = Carbon::parse($request->input('calculate_date'));
        $billing = $this->search([
            'all' => true,
            'billing_agent_id' => $billingAgentId,
            'calculate_date' => $request->input('calculate_date'),
        ])->first();
        $receipts =  $this->receiptListByBillingAgentYearMonth(
            $billingAgentId,
            $calculateDate,
        );
        $billingReceipts = $this->getBillingReceipts($receipts, $billing);

        $assign = [
            'billingReceipts' => $billingReceipts,
            'billing' => $billing,
        ];
        $date = Carbon::now()->format('YmdHis');
        $path = 'file/csv/billing/請求書' . $date . '.csv';
        Excel::store(new BillingAgentYearMonthExport($assign), $path);
        $url = Storage::url($path);

        return url($url);
    }

    /**
     * Export csv
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function exportCsvByYearMonth(Request $request)
    {
        $calculateDate = Carbon::parse($request->input('calculate_date'));

        $billings = $this->repository
            ->whereYear('calculate_date', '=', $calculateDate->format('Y'))
            ->whereMonth('calculate_date', '=', $calculateDate->format('m'))
            ->get();
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
        ];

        $date = Carbon::now()->format('YmdHis');
        $path = 'file/csv/billing/請求一覧表' . $date . '.csv';
        Excel::store(new YearMonthExport($assign), $path);
        $url = Storage::url($path);

        return url($url);
    }

    /**
     * Export csv
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function exportCsvBillingAgentCollations(Request $request)
    {
        $billingAgentId = $request->input('billing_agent_id');
        $calculateDate = Carbon::parse($request->input('calculate_date'));

        $agents = Agent::where('billing_agent_id', $billingAgentId)->get();
        $receipts = $this->receiptListByBillingAgentYearMonth($billingAgentId, $calculateDate);
        $rows = $this->getCollations($agents, $receipts);

        $date = Carbon::now()->format('YmdHis');
        $path = 'file/csv/billing/請求照合表' . $date . '.csv';
        Excel::store(new BillingAgentCollations($rows), $path);
        $url = Storage::url($path);

        return url($url);
    }

    /**
     * Get payment last deposit_amount
     * @param \Illuminate\Http\Request $request
     *
     * @return $billing
     */
    public function getDepositAmountOfAgent(Request $request)
    {
        $paymentMonth = Carbon::parse($request->input('payment_month'))->format('Y/m/d');
        $billing = $this->repository->where('billing_agent_id', $request->input('agent_id'))->where('calculate_date', $paymentMonth)->first();
        return $billing ? $billing->billing_amount : 0;
    }

    /**
     * Get lastBilledAmount
    */
    public function lastBilledAmount($billingAgentId, $calculateDate)
    {
        $lastBilledAmount = 0;
        if ($billingAgentId && $calculateDate) {
            $totalDeposit = $this->depositRepository
                        ->where('billing_agent_id', $billingAgentId)
                        ->where('payment_date', '<', $calculateDate)
                        ->sum('amount');

            $totalAmountBilling = $this->repository
                                ->where('billing_agent_id', $billingAgentId)
                                ->where('calculate_date', '<', $calculateDate)
                                ->sum('last_billed_amount');
            $lastBilledAmount = $totalDeposit - $totalAmountBilling;
            return $lastBilledAmount;
        }

        return $lastBilledAmount;
    }
    /*
     * Print billing agent year month
     * @param \Illuminate\Http\Request $request
     *
     * @return url pdf
     */
    public function printBillingAgentYearMonth(Request $request)
    {
        $conditions = [
            'billing_agent_id' => $request->input('billing_agent_id'),
            'or_agent_id' => $request->input('billing_agent_id'),
            'transaction_year_month' => $request->input('calculate_date'),
            'all' => true,
        ];

        $receipts = $this->receiptService->search($conditions);
        $company = $this->companyService->company();
        $agent = $this->agentService->find($request->input('billing_agent_id'));

        $billing = $this->search($conditions)->first();

        $billingReceipts = $this->getBillingReceipts($receipts, $billing);

        $billingReceipts = array_chunk($billingReceipts, 20);
        $pages = count($billingReceipts);
        $urls = [];
        $date = Carbon::now()->format('YmdHis');

        foreach ($billingReceipts as $key => $billingReceipt)
        {
            $assigns = [
                'company' => $company,
                'billingReceipts' => $billingReceipt,
                'lastDateOfMonth' => Carbon::now()->endOfMonth()->format('Y年 m月 d日'),
                'agent' => $agent,
                'billing' => $billing,
                'pages' => [
                    $key + 1  => $pages,
                ],
            ];

            $fileName = '請求書' . '_' . $date . $key . '.pdf';

            $url = (new PrintBillingAgentYearMonthExport($assigns))
                ->setConfig([])
                ->changePath(SAVE_PATH_FILE_PRINT_BILLING_AGENT_YEAR_MONTH)
                ->savePdf($fileName);

            array_push($urls, url($url));
        }
        return ['result' => $urls];
    }

    /**
     * Print list by year month
     * @param \Illuminate\Http\Request $request
     *
     * @return url pdf
     */
    public function printListByYearMonth(Request $request)
    {
        $billings = $this->search(['all' => true]);
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

        $assigns = [
            'billings' => $billings,
            'totalBillings' => $totalBillings,
            'createdDate'=> Carbon::now()->format('Y/m/d H:i:s'),
            'defaultYearMonth' => Carbon::parse($request->input('calculate_date'))->format('Y/m'),
        ];

        $date = Carbon::now()->format('YmdHis');
        $fileName = '請求一覧表' . '_' . $date . '.pdf';
        $url = (new PrintListByYearMonthExport($assigns))
                ->setConfig([])
                ->changePath(SAVE_PATH_FILE_PRINT_BILLING_BY_YEAR_MONTH)
                ->savePdf($fileName);
        return ['result' => url($url)];
    }

    /**
     * Print list collations
     * @param \Illuminate\Http\Request $request
     *
     * @return url pdf
     */
    public function exportListCollations(Request $request)
    {
        $company = $this->companyService->company();
        $agent = $this->agentService->find($request->input('billing_agent_id'));
        $collations = $this->listBillingAgentCollations($request->input('billing_agent_id'), $request->input('calculate_date'));

        $collations = array_chunk($collations, 30);

        $pages = count($collations);
        $urls = [];
        foreach($collations as $key => $collation)
        {
            $assigns = [
                'company' => $company,
                'billingAgent' => $agent,
                'collations' => $collation,
                'month' => Carbon::parse($request->input('calculate_date'))->format('m'),
                'pages' => [
                    $key + 1  => $pages,
                ],
            ];

            $date = Carbon::now()->format('YmdHis');
            $fileName = '請求照合表' . '_' . $date . $key . '.pdf';
            $url = (new PrintListCollations($assigns))
                    ->setConfig([])
                    ->changePath(SAVE_PATH_FILE_PRINT_BILLING_LIST_COLLATIONS)
                    ->savePdf($fileName);

            $urls[$key] = url($url);
        }
        return ['result' => $urls];
    }

    /**
     * Print list by batch
     * @param \Illuminate\Http\Request $request
     *
     * @return url pdf
     */
    public function printListByBatch($conditions = [])
    {
        $this->makeBuilder($conditions);
        $this->builder->with(['billingAgent.receipts' => function ($query){
            $transactionDate = Carbon::parse($this->filter->get('calculate_date'));
            return $query
                ->whereYear('transaction_date', '=', $transactionDate->format('Y'))
                ->whereMonth('transaction_date', '=', $transactionDate->format('m'));
        }]);

        $this->builder->with(['billingAgent.childAgents' => function ($query){
            return $query->with('receipt');
        }]);


        if ($this->filter->has('calculate_date')) {
            $this->builder->where('calculate_date', $this->filter->get('calculate_date'));

            $this->cleanFilterBuilder('calculate_date');
        }

        if ($this->filter->has('billing_agent_ids')) {
            $this->builder->whereIn('billing_agent_id', $this->filter->get('billing_agent_ids'));

            $this->cleanFilterBuilder('billing_agent_ids');
        }


        $billings = $this->endFilter();

        $company = $this->companyService->company();

        $pdfs = [];

        //Setup data export
        foreach($billings as $billing)
        {
            //Setup data receipts
            $receipts = [];

            foreach($billing->billingAgent->receipts as $receipt)
            {
                if ($receipt) {
                    array_push($receipts, $receipt);
                }
            }

            foreach($billing->billingAgent->childAgents as $agent)
            {
                if ($agent->receipt) {
                    array_push($receipts, $agent->receipt);
                }
            }

            //Setup billing receipts
            $billingReceipts = $this->getBillingReceipts($receipts, $billing);
            //Setup page billing
            $billingReceipts = array_chunk($billingReceipts, 20);
            $pages = count($billingReceipts);

            foreach($billingReceipts as $k => $receipt){
                $assign = [
                    'lastDateOfMonth' => Carbon::now()->endOfMonth()->format('Y年 m月 d日'),
                    'company' => $company,
                    'agent' => $billing->billingAgent,
                    'billing' => $billing,
                    'lastBilledAmount' => $this->lastBilledAmount($billing->billingAgent->id, $billing->calculate_date),
                    'billingReceipts' => $receipt,
                    'pages' => [
                        $k + 1  => $pages,
                    ],
                ];

                //Setup filename
                $date = Carbon::now()->format('YmdHis');
                $fileName = '請求書ー括出力' . '_' . $date . '.pdf';

                //Export pdf
                $pdf = (new PrintListByPatch($assign))
                        ->setConfig([])
                        ->changePath(SAVE_PATH_FILE_PRINT_LIST_BY_PATCH)
                        ->savePdf($fileName);

                array_push($pdfs, url($pdf));
            }
        }

        return ['result' => $pdfs];
    }
}
