<?php

namespace App\Repositories;

use App\Models\Receipt;
use App\Repositories\Concerns\BaseRepository;

class ReceiptRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Receipt::class;
    }

    /**
     * Plus 1 code receipt
    */
    public function getIncrementalCode($typeCode)
    {
        return $this->where('type_code', $typeCode)->withTrashed()->max('code') + 1;
    }
}
