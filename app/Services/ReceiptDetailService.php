<?php

namespace App\Services;

use App\Repositories\ReceiptDetailRepository;
use App\Services\Concerns\BaseService;
use Exception;


class ReceiptDetailService extends BaseService
{
    /**
     * @param \App\Repositories\ReceiptDetailRepository $repository
     */
    public function __construct(ReceiptDetailRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $products
    */
    public function updateDetailReceipts($products)
    {
        foreach ($products as $product) {
            if (!empty($product['id'])) {
                $detailReceipt = $this->repository->find($product['id']);
                $this->repository->updateByModel($detailReceipt, $product);
            }else{
                $detailReceipt = $this->repository->create($product);
            }
        }

        return $detailReceipt;
    }

    public function removeProducts($productRemove)
    {
        $receiptDetails = $this->repository->find($productRemove);
        foreach ($receiptDetails as $receiptDetail) {
            $result = $receiptDetail->delete();
        }
        return $result;
    }
}
