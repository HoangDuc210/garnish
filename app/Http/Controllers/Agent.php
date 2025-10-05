<?php

namespace App\Http\Controllers;

use App\Enums\PaginationPage;
use App\Http\Requests\AgentStoreRequest;
use App\Services\AgentService;
use App\Services\CompanyService;
use App\Services\Traits\OptionExport;
use Illuminate\Http\Request;

class Agent extends Controller
{
    use OptionExport;
    /**
     * @var \App\Services\AgentService
     */
    protected $agentService;

    /**
     * @var \App\Services\CompanyService
     */
    protected $companyService;

    /**
     * @param \App\Services\AgentService $agentService
     */
    public function __construct(AgentService $agentService, CompanyService $companyService)
    {
        $this->agentService = $agentService;
        $this->companyService = $companyService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $this->getDataExportCsv(
            [
                'url' => route(AGENT_EXPORT_CSV_ROUTE),
                'btn' => 'agent-export-csv'
            ]
        );
        $this->getDataSelectLimit(route(AGENT_ROUTE));
        $dataOptions = $this->getDataOptionExport();

        $assign = [];
        $assign['dataOptions'] = $dataOptions;
        $assign['agents'] = $this->agentService->search();

        return view('agent.index', $assign);
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
        $keyword = $request->filled('q') ? ['keyword' =>  $request->input('q')] : [];

        $results = $this->agentService->search($keyword + ['limit' => 20, 'all' => true]);

        return response()->json(['results' => $results]);
    }

    /**
     * Search
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBillingAgents(Request $request)
    {
        $data = $this->agentService->getBillingAgents();

        return response()->json([
            'result' => true,
            'data' => $data,
        ]);
    }


    /**
     * Get detail agent
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(Request $request)
    {
        $result = $this->agentService->find($request->input('id'));

        return response()->json(['result' => $result]);
    }

    /**
     * Search code for agent
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchCode(Request $request)
    {
        $result = $this->agentService->searchCode($request->input('code'));

        if (empty($result)) {
            return response()->json(['message' => 'No result'], 404);
        }
        return response()->json(['result' => $result]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $assign = [
            'company' => $this->companyService->company(),
        ];
        return view('agent.create', $assign);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $agent = $this->agentService->find($id);

        $agent['agent'] = $agent->billing_agent_id ? ['id' => $agent->billing_agent_id] : ['id' => $agent->id];
        $agent['is_parent'] = $agent->billing_agent_id == $agent->id ? true : false;

        // Save detail to session
        if (empty(request()->old()) || old('id') != $id) {
            $this->flashSession($agent);
        }

        $assign = [
            'company' => $this->companyService->company(),
        ];

        return view('agent.edit', $assign);
    }

    /**
     * @param \App\Http\Requests\AgentStoreRequest $request
     */
    public function store(AgentStoreRequest $request)
    {
        $this->agentService->store($request);

        return redirect()->route(AGENT_ROUTE);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->agentService->delete($id);

        return redirect()->route(AGENT_ROUTE)->with('message', trans('agent.deleted'));
    }

    /**
     * Export list order
     * @param \Illuminate\Http\Request $request
     *
     * * @return \Illuminate\Http\RedirectResponse
     */
    public function exportCSV(Request $request)
    {
        app()->setLocale('ja');
        $url = $this->agentService->exportCSV($request);

        return response()->json(['result' => $url], 200);
    }

    /**
     * Search modal agent
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchModal(Request $request)
    {
        $results = $this->agentService->search();

        return response()->json(['results' => $results]);
    }
}
