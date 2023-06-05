<?php

namespace App\Services\Product;

use App\Filters\IsSales;
use App\Filters\ProductMaxPrice;
use App\Filters\ProductMinPrice;
use App\Filters\ProductName;
use App\Repositories\ProductRepository;
use Mi\L5Core\Criteria\FilterCriteria;
use Mi\L5Core\Criteria\OrderCriteria;
use Mi\L5Core\Criteria\WithRelationsCriteria;
use Mi\L5Core\Services\BaseService;

class ListProductService extends BaseService
{
    protected $collectsData = true;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Logic to handle the data
     */
    public function handle()
    {
        $data = $this->repository->pushCriteria(new FilterCriteria($this->data->toArray(), $this->allowFilters()))
        ->pushCriteria(new WithRelationsCriteria($this->data->get('with'), $this->repository->allowRelations()))
        ->pushCriteria(new OrderCriteria($this->data->get('order', '-created_at')));
        return $this->data->has('per_page')
            ? $data->paginate((int)$this->getPerPage())
            : $data->all();
    }

    private function allowFilters()
    {
        return [
            'product_name' => ProductName::class,
            'product_min_price' => ProductMinPrice::class,
            'product_max_price' => ProductMaxPrice::class,
            'is_sales' => IsSales::class,
        ];
    }
}
