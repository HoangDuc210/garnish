<?php

namespace App\Services;

use App\Exports\ProductExport;
use App\Repositories\ProductRepository;
use App\Services\Concerns\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductService extends BaseService
{
    /**
     * @param \App\Repositories\ProductRepository $repository
     */
    public function __construct(ProductRepository $repository)
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

        $this->builder->with(['unit']);

        if ($this->filter->has('code')) {
            $this->builder->where('code', 'LIKE', '%' . $this->filter->get('code') . '%');

            $this->cleanFilterBuilder('code');
        }

        if ($this->filter->has('name')) {
            $this->builder->where('name', 'LIKE', '%' . $this->filter->get('name') . '%');

            $this->cleanFilterBuilder('name');
        }

        if ($this->filter->has('ids')) {
            $this->builder->whereIn('id', $this->filter->get('ids'));

            $this->cleanFilterBuilder('ids');
        }

        $this->builder->orderBy('code', 'ASC');

        return $this->endFilter();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed|void
     * @throws \Prettus\Validator\Exceptions\ValidatorException|\Prettus\Repository\Exceptions\RepositoryException
     */
    public function store(Request $request)
    {
        $idRequest = $request->input('id', 0);

        $data = $request->toArray();

        // Create product
        if (!$idRequest || $idRequest == 0) {
            $result = $this->repository->create($data);

            $this->withSuccess(trans('product.product_created'));

            return $result;
        }

        //Edit product
        $product = $this->find($idRequest);

        $this->withSuccess(trans('product.product_updated'));

        return $this->repository->updateByModel($product, $data);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed|void
     * @throws \Prettus\Validator\Exceptions\ValidatorException|\Prettus\Repository\Exceptions\RepositoryException
     */
    public function searchAjax($conditions = [])
    {
        $this->makeBuilder($conditions);
        $this->builder->with('unit');

        if ($this->filter->has('id')) {
            $this->builder->where('id', $this->filter->get('id'));

            $this->cleanFilterBuilder('id');
        }

        $this->builder->code($this->filter->get('code'));
        $this->builder->name($this->filter->get('name'));

        return $this->builder->first();
    }

    /**
     * Export list order csv
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @return true or false
     */
    public function exportCSV(Request $request)
    {
        $listProduct = $request->input('ids') == 'all' ? $this->repository->with(['unit'])->get() : $this->repository->find($request->input('ids'), ['unit']);
        $date = Carbon::now()->format('YmdHis');
        $path = SAVE_PATH_FILE_CSV_PRODUCT . '商品'. $date .'.csv';

        Excel::store(new ProductExport($listProduct), $path);
        $url = Storage::url($path);

        return url($url);
    }
}
