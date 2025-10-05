<?php

namespace App\Services;

use App\Exports\AgentExport;
use App\Repositories\AgentRepository;
use App\Repositories\CompanyRepository;
use App\Services\Concerns\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;

class AgentService extends BaseService
{
    /**
     * @param \App\Repositories\AgentRepository $agentRepository
     */
    protected $agentRepository;

    /**
     * @param \App\Repositories\CompanyRepository $agentRepository
     */
    protected $companyRepository;

    public function __construct(AgentRepository $agentRepository, CompanyRepository $companyRepository)
    {
        $this->repository = $agentRepository;
        $this->companyRepository = $companyRepository;
    }

    /**
     * Export all agents
     */
    protected $agentList;

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

        $this->builder->with(['billingAgent']);

        if ($this->filter->has('name')) {
            $this->builder->where('name', 'LIKE', '%' . $this->filter->get('name') . '%');

            $this->cleanFilterBuilder('name');
        }

        if ($this->filter->has('code')) {
            $this->builder->where('code', $this->filter->get('code'));

            $this->cleanFilterBuilder('code');
        }

        if ($this->filter->has('parent')) {
            $this->builder->whereHas('billingAgent', function (Builder $q) {
                return $q->where('name', 'LIKE', '%' . $this->filter->get('parent') . '%');
            });

            $this->cleanFilterBuilder('parent');
        }

        if ($this->filter->has('address')) {
            $this->builder->where('address', 'LIKE', '%' . $this->filter->get('address') . '%');
            $this->cleanFilterBuilder('address');
        }

        $this->builder->orderBy('code', 'ASC');

        return $this->endFilter();
    }

    /**
     * getBillingAgents
     *
     * @return mixed
     */
    public function getBillingAgents()
    {
        $billingAgentIds = $this->repository->get()->pluck('billing_agent_id');
        $billingAgents = $this->repository
            ->whereIn('id', $billingAgentIds)
            ->orderBy('code', 'asc')
            ->get();

        return $billingAgents;
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
        $idRequest = $request->input('id', 0);

        $data = $request->toArray();

        //Set value billing_source_company_id by value company
        $data['billing_source_company_id'] = '1';
        //Set value parent
        $data['billing_agent_id'] = $request->input('agent.id') ? $request->input('agent.id') : $this->repository()->getByIdNextRecord();

        $data['collection_rate'] = number_format($request->input('collection_rate'), 2);

        // Create product
        if (!$idRequest || $idRequest == 0) {
            $result = $this->repository->create($data);

            $this->withSuccess(trans('agent.created'));

            return $result;
        }

        //Edit
        if (!is_null($this->checkUniqueCodeUpdate($request))) {
            $this->withErrors('得意先番号「'. $request->input('code') .'」は既に登録されています。');
            return redirect()->route(AGENT_EDIT_ROUTE, $request->input('id'));
        }


        $agent = $this->find($idRequest);

        $this->withSuccess(trans('agent.updated'));

        return $this->repository->updateByModel($agent, $data);
    }

    /**
     * Search code for agent
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     */
    public function searchCode($code)
    {
        return $this->repository->getAgentByCode($code);
    }

    /**
     * Export list order csv
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @return true or false
     */
    public function exportCSV(Request $request)
    {
        $agentList = $request->input('ids') != 'all' ?
        $this->repository->whereIn('id', $request->input('ids'))->orderBy('code', 'ASC')->get() :
        $this->repository->orderBy('code', 'ASC')->get();

        $date = Carbon::now()->format('YmdHis');
        $path = SAVE_PATH_FILE_CSV_AGENT . '得意先' . $date . '.csv';
        Excel::store(new AgentExport($agentList->toArray()), $path);
        $url = Storage::url($path);

        return url($url);
    }

    /**
     * Check unique code
     * @param \Illuminate\Http\Request $request
    */
    private function checkUniqueCodeUpdate(Request $request)
    {
        $agent = $this->repository
                ->where('id', '!=', $request->input('id'))
                ->where('code', $request->input('code'))->first();
        return $agent;
    }
}
