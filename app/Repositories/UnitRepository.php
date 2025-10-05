<?php

namespace App\Repositories;

use App\Models\Unit;
use App\Repositories\Concerns\BaseRepository;

class UnitRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Unit::class;
    }
}
