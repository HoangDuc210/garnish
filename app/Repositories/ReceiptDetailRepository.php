<?php

namespace App\Repositories;

use App\Models\ReceiptDetail;
use App\Repositories\Concerns\BaseRepository;

/**
 * Class ReceiptDetailRepositoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ReceiptDetailRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ReceiptDetail::class;
    }
}
