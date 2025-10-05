<?php

namespace App\Services;

use App\Repositories\UnitRepository;
use App\Services\Concerns\BaseService;
use Exception;
use Illuminate\Support\Facades\DB;

class UnitService extends BaseService
{
    /**
     * @param \App\Repositories\UnitRepository $repository
     */
    public function __construct(UnitRepository $repository)
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
        $this->builder->orderBy('id', 'asc');
        return $this->endFilter();
    }
}
