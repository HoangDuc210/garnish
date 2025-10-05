<?php

namespace App\Repositories;

use App\Models\AccessHistory;
use App\Repositories\Concerns\BaseRepository;

class AccessHistoryRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AccessHistory::class;
    }
}
