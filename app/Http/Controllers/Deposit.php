<?php

namespace App\Http\Controllers;

use App\Enums\SendMoneyType;
use App\Http\Requests\Deposit\StoreDepositRequest;
use App\Http\Requests\Deposit\UpdateDepositRequest;
use App\Services\DepositService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\General\Functions;
use App\Models\Agent;
use App\Services\BillingService;

class Deposit extends Controller
{
    /**
     * @var \App\Services\DepositService
     */
    protected $depositService;
    protected $billingService;

    /**
     * @param \App\Services\DepositService $depositService
     */
    public function __construct(DepositService $depositService, BillingService $billingService)
    {
        $this->depositService = $depositService;
        $this->billingService = $billingService;
    }
    /**
     * Return view list deposits
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $yearMonthOptions = Functions::makeYearMonthOptions();
        $defaultYearMonth = array_key_first($yearMonthOptions);
        $billingAgentId = $request->billing_agent_id ? $request->billing_agent_id : 0;
        $yearMonth = $request->payment_year_month ? $request->payment_year_month: $defaultYearMonth;
        $billingAgent = Agent::find($billingAgentId);
        $searchParams = [
            'payment_year_month' => $yearMonth,
            'order_by' => 'payment_date',
            'order_direction' => 'desc',
        ];
        if ($billingAgentId) {
            $searchParams['billing_agent_id'] = $billingAgentId;
        }
        $deposits = $this->depositService->search($searchParams);

        $assign = [
            'deposits' => $deposits,
            'billingAgent' => $billingAgent,
            'yearMonthOptions' => $yearMonthOptions,
            'defaultYearMonth' => $defaultYearMonth,
        ];
        return view('deposits.index', $assign);
    }

    /**
     * Store data
     * @param Request
     * @return \Illuminate\Contracts\View\View
     */
    public function store(StoreDepositRequest $request)
    {
        $this->depositService->store($request);
        $data = [
            'input_billing_agent_id' => $request->input('agent.id'),
            'billing_agent_id' => $request->input('agent.id'),
            'payment_year_month' =>
                Carbon::createFromFormat('Y/m', $request->input('payment_date'))
                ->firstOfMonth()
                ->format('Y-m-d'),
        ];
        return redirect()->route(DEPOSIT_ROUTE, $data);
    }

    /**
     * delete data
     * @param Request
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        $assign = [
            'paymentFirst' => Carbon::parse()->format('Y/m'),
            'currencies' => SendMoneyType::options(),
        ];
        return view('deposits.create', $assign);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $deposit = $this->depositService->find($id);

        $assign = [
            'deposit' => $deposit,
        ];

        return view('deposits.edit', $assign);
    }

    /**
     * Update data
     * @param Request
     * @return \Illuminate\Contracts\View\View
     */
    public function update(UpdateDepositRequest $request)
    {
        $this->depositService->updateRow($request);

        return redirect()->route(
            DEPOSIT_ROUTE,
            [
                'billing_agent_id' => $request->input('billing_agent_id'),
                'payment_year_month' => $request->input('payment_year_month'),
            ]
        )
            ->with(['message' => trans('deposit.updated')])
            ->withInput();
    }

    /**
     * Delete a product
     *
     * @param \Illuminate\Http\Request $request
     * @param id $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id, $billingAgentId, $paymentYearMonth)
    {
        $this->depositService->delete((int)$id);

        return redirect()->route(
            DEPOSIT_ROUTE,
            [
                'billing_agent_id' => $billingAgentId,
                'payment_year_month' => $paymentYearMonth,
            ]
        )
            ->with(['message' => trans('deposit.deleted')])
            ->withInput();
    }


    /**
     * Search ajax
     * @param \Illuminate\Http\Request $request
     *
     * @return $result
    */
    public function searchAjax(Request $request)
    {
        $result = $this->depositService->searchAjax();
        return response()->json(['results' => $result], 200);
    }

    /**
     * Get deposit amount of agent
    */
    public function getDepositAmountOfAgent(Request $request)
    {
        $totalAmount = $this->depositService->getDepositAmountOfAgent($request);
        return response()->json(['result' => $totalAmount]);
    }

}
