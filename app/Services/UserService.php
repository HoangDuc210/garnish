<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Services\Concerns\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    /**
     * @param \App\Repositories\UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
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

        if ($this->filter->has('username')) {
            $this->builder->where('username', 'LIKE', '%' . $this->filter->get('username') . '%');

            $this->cleanFilterBuilder('username');
        }

        return $this->endFilter();
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

        $data = $request->except(['password']);
        $data['password'] = Hash::make($request->input('password'));

        // Create product
        if (!$idRequest || $idRequest == 0) {
            $result = $this->repository->create($data);

            $this->withSuccess(trans('user.created'));

            return $result;
        }

        //Edit product
        $record = $this->find($idRequest);

        //Remove password
        if (!$request->filled('password')) {
            unset($data['password']);
        }

        $this->withSuccess(trans('user.updated'));

        return $this->repository->updateByModel($record, $data);
    }
}
