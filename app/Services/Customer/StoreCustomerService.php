<?php

namespace App\Services\Customer;

use App\Repositories\CustomerRepository;
use Mi\L5Core\Services\BaseService;

class StoreCustomerService extends BaseService
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
        $dataCustomer = $this->data->only([
            'customer_name',
            'email',
            'tel_num',
            'is_active',
            'address',
        ])->toArray();
        return $this->repository->create($dataCustomer);
    }
}
