<?php

namespace App\Repositories;

use App\Models\Deposit;
use App\Repositories\Concerns\BaseRepository;

class DepositRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Deposit::class;
    }
}
