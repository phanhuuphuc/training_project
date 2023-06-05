<?php

namespace App\Services\Customer;

use App\Filters\Address;
use App\Filters\Email;
use App\Filters\IsActive;
use App\Filters\CustomerName;
use App\Repositories\CustomerRepository;
use Mi\L5Core\Criteria\FilterCriteria;
use Mi\L5Core\Criteria\OrderCriteria;
use Mi\L5Core\Criteria\WithRelationsCriteria;
use Mi\L5Core\Services\BaseService;

class ListCustomerService extends BaseService
{
    protected $collectsData = true;

    public function __construct(CustomerRepository $repository)
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
        ->pushCriteria(new OrderCriteria($this->data->get('order', '-customer_id')));

        return $this->data->has('per_page')
            ? $data->paginate((int)$this->getPerPage())
            : $data->all();
    }

    private function allowFilters()
    {
        return [
            'customer_name' => CustomerName::class,
            'email' => Email::class,
            'address' => Address::class,
            'is_active' => IsActive::class,
        ];
    }
}
