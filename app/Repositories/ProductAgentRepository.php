<?php

namespace App\Repositories;

use App\Models\ProductAgent;
use App\Repositories\Concerns\BaseRepository;

/**
 * Class ReceiptDetailRepositoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ProductAgentRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ProductAgent::class;
    }
}
