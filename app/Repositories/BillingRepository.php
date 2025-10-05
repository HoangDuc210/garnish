<?php

namespace App\Repositories;

use App\Models\Billing;
use App\Repositories\Concerns\BaseRepository;

class BillingRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Billing::class;
    }
}
