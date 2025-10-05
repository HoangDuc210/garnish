<?php

namespace App\Services;

use App\Http\Requests\Company\StoreRequest;
use App\Repositories\CompanyRepository;
use App\Services\Concerns\BaseService;
use Illuminate\Http\Request;

class CompanyService extends BaseService
{

    /**
     * @var \App\Services\CompanyRepository
     */
    protected $repository;

    /**
     * @param \App\Repositories\CompanyRepository $repository;
     */
    public function __construct(CompanyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get company
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function company()
    {
        $company = $this->repository->first();
        return $company ? $company : [];
    }

    /**
     * Store company
     * @param \App\Http\Requests\Company\StoreRequest $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(StoreRequest $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return $this->create($request);
        }

        return $this->edit($request);
    }

    /**
     * Create company
     * @param \App\Http\Requests\Company\StoreRequest $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    protected function create(StoreRequest $request)
    {
        $result = $this->repository->create($request->toArray());

        $this->withSuccess(trans('company.created'));

        return $result;
    }

    /**
     * Edit company
     * @param \App\Http\Requests\Company\StoreRequest $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    protected function edit(StoreRequest $request)
    {
        $id = $request->input('id');
        $data = $request->toArray();

        $result = $this->repository->update($data, $id);

        $this->withSuccess(trans('company.updated'));

        return $result;
    }
}
