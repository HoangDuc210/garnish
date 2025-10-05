<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Concerns\BaseRepository;

class ProductRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Product::class;
    }
}
