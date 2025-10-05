<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Concerns\BaseRepository;

class UserRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }
}
