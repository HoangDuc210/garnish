<?php

namespace App\Services;

use App\Http\Requests\Deposit\StoreDepositRequest;
use App\Repositories\BillingRepository;
use App\Repositories\DepositRepository;
use App\Services\Concerns\BaseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class DepositService extends BaseService
{
    /**
     * @param \App\Repositories\DepositRepository $repository
     * @param \App\Repositories\BillingRepository $billingRepository
     */
    protected $billingRepository;

    public function __construct(
        DepositRepository $repository,
        BillingRepository $billingRepository
        )
    {
        $this->repository = $repository;
        $this->billingRepository = $billingRepository;
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

        if ($this->filter->has('billing_agent_id')) {
            $this->builder->where('billing_agent_id', $this->filter->get('billing_agent_id'));

            $this->cleanFilterBuilder('billing_agent_id');
        }

        // Search by payment_year_month
        if ($this->filter->has('payment_year_month')) {
            $paymentDate = Carbon::parse($this->filter->get('payment_year_month'));
            $this->builder
                ->whereYear('payment_date', '=', $paymentDate->format('Y'))
                ->whereMonth('payment_date', '=', $paymentDate->format('m'));
            $this->cleanFilterBuilder('payment_year_month');
        }

        // order_by
        if ($this->filter->has('order_by')) {
            $direction =
                $this->filter->has('order_direction') ?
                $this->filter->get('order_direction') : 'desc';
            $this->builder->orderBy($this->filter->get('order_by'), $direction);
            $this->cleanFilterBuilder('order_by');
        }


        $result = $this->endFilter();

        foreach ($result as $item) {
            $item['billing_agent_code'] = $item->billing_agent_code;
        }

        return $result;
    }

    /**
     * Get all deposit
     */
    public function deposits()
    {
        return $this->repository->get();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed|void
     * @throws \Prettus\Validator\Exceptions\ValidatorException|\Prettus\Repository\Exceptions\RepositoryException
     */
    public function searchAjax($conditions = [])
    {
        $this->makeBuilder($conditions);
        //Search deposit by payment_month
        if ($this->filter->has('payment_date')) {
            $this->builder->where('payment_date', 'LIKE', '%' . $this->filter->get('payment_date') . '%');
            $this->cleanFilterBuilder('payment_date');
        }
        //Search deposit by agent id
        if ($this->filter->has('agent_id')) {
            $this->builder->where('billing_agent_id', $this->filter->get('agent_id'));
            $this->cleanFilterBuilder('agent_id');
        }

        //Search deposit by agent id
        if ($this->filter->has('agent_code')) {
            $this->builder->whereHas('billingAgent', function (Builder $q){
                $q->where('code', $this->filter->get('agent_code'));
            });
            $this->cleanFilterBuilder('agent_code');
        }

        return $this->endFilter();
    }

    /**
     * Store deposit
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(Request $request)
    {
        $id = $request->input('id');

        DB::beginTransaction();
        try {

            if (!$id || $id == 0) {
                $result = $this->create($request);
            }else{
                $result = $this->update($request);
            }

            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }

        return$result;
    }

    /**
     * Create a new deposit
     * @param \Illuminate\Http\StoreDepositRequest $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function create(StoreDepositRequest $request)
    {
        $results = [];

        $deposits = $request->input('deposits');

        foreach($deposits as $deposit){
            $paymentDate = str_replace('/', '-', $request->input('payment_date')) . '-' . $deposit['payment_date'];
            $data = [
                'billing_agent_id' => $request->input('agent.id'),
                'payment_date' => $paymentDate,
                'type_code' => $deposit['type_code'],
                'amount' => str_replace(',', '', $deposit['amount']),
                'memo' => $deposit['memo']
            ];

            $result = $this->repository->create($data);
            array_push($results, $result);
        }

        $this->withSuccess(trans('deposit.created'));

        return $results;
    }


    /**
     * update deposit
     * @param data
     *
     * @return mixed
     */
    public function updateRow(Request $request)
    {
        $deposit = $this->find($request->input('id'));
        $data = $request->all();

        return $this->repository->update($data, $deposit->id);
    }

    /**
     * Create a new deposit
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(Request $request)
    {
        $depositsDetail = [];

        foreach ($request->input('deposits') as $deposit)
        {
            $data = [
                'agent_id' => $request->input('agent.id'),
                'payment_date' => str_replace('/', '-', $deposit['payment_date']),
                'type_code' => $deposit['type_code'],
                'amount' => str_replace(',', '', $deposit['amount']),
                'memo' => $deposit['memo'],
            ];
            array_push($depositsDetail, $data);
        }
        $deposit = $this->repository->find($request->input('id'));

        $deposit->depositsDetail()->sync($depositsDetail);

        $this->withSuccess(trans('deposit.updated'));

        return $deposit;
    }

    /**
     * Get deposit amount of agent
    */
    public function getDepositAmountOfAgent(Request $request)
    {
        $totalDeposit = $this->repository
                        ->where('billing_agent_id', $request->input('agent_id'))
                        ->where('payment_date', '<=', $request->input('payment_date'))
                        ->sum('amount');

        $totalAmountBilling = $this->billingRepository
                            ->where('billing_agent_id', $request->input('agent_id'))
                            ->where('calculate_date', '<=', $request->input('payment_date'))
                            ->sum('last_billed_amount');


        return $totalDeposit - $totalAmountBilling;
    }
}
