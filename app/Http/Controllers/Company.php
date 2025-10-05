<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\StoreRequest;
use App\Services\CompanyService;

class Company extends Controller
{
    /**
     * @var \App\Services\CompanyService
     */
    protected $companyService;

    /**
     * @param \App\Services\CompanyService $companyService
     */
    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    /**
     * Filter and show list user
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $company = $this->companyService->company();

        if (empty(request()->old()) || old('id') != $company->id) {
            $this->flashSession($company);
        }

        return view('company.index');
    }

    /**
     * handle form
     *
     * @param \App\Http\Requests\Company\StoreRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        $this->companyService->store($request);

        return redirect()->back();
    }

}
