<?php

namespace App\Services;

use App\Repositories\ProductAgentRepository;
use App\Services\Concerns\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductAgentService extends BaseService
{
    /**
     * @param \App\Repositories\ProductAgentRepository $repository
     */
    public function __construct(ProductAgentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Filter user by request
     *
     * @param array $conditions
     *
     * @return mixed
     */
    public function search($conditions = [])
    {
        $this->makeBuilder($conditions);
        $this->builder->with(['product', 'unit']);
        $this->builder->agent($this->filter->get('agent_id'));
        if ($this->filter->has('product_code')) {
            $this->builder->whereHas('product', function(Builder $q){
                return $q->where('code', $this->filter->get('product_code'));
            });
        }
        if ($this->filter->has('product_name')) {
            $this->builder->whereHas('product', function(Builder $q){
                return $q->where('name', $this->filter->get('product_name'));
            });
        }

        if ($this->filter->has('product_name')) {
            $this->builder->where('unit_id', $this->filter->get('unit_id'));
        }
        return $this->builder->orderBy('id', 'desc')->first();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed|void
     * @throws \Prettus\Validator\Exceptions\ValidatorException|\Prettus\Repository\Exceptions\RepositoryException
     */
    public function store($data)
    {
        $products = $data['receipt_details'];
        $agentId = $data['agent_id'];
        foreach ($products as $item)
        {
            $conditions = [
                'agent_id' => $agentId,
                'product_code' => $item['code'],
                'product_id' => $item['product_id'] ?? 0,
                'unit_id' => $item['unit_id'],
            ];
            //Setup search
            if ($item['product_id'] > 0 && $item['product_id'] != null)
            {
                $product = $this->search($conditions);
                //Setup create or update
                $conditions['price'] = $item['price'];
                if (!$product) {

                    $product = $this->repository->create($conditions);
                }else{

                    $product = $this->repository->update($conditions, $product->id);
                }
            }
            $product = $this->repository->create($conditions);
        }
        return $product;
    }
}