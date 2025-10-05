<?php

namespace App\Repositories;

use App\Models\Agent;
use App\Repositories\Concerns\BaseRepository;

class AgentRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Agent::class;
    }

    public function getAgentByCode($code)
    {
        return $this->where('code', $code)->first();
    }

    /**
     * Get by id next record
    */
    public function getByIdNextRecord()
    {
        return $this->max('id') + 1;
    }
}
