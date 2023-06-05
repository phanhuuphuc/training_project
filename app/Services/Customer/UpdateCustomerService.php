<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Repositories\CustomerRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mi\L5Core\Services\BaseService;

class UpdateCustomerService extends BaseService
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
        $customer = Customer::where('customer_id', $this->model)->first();
        throw_if(!$customer, new ModelNotFoundException());

        $dataCustomer = $this->data->only([
            'customer_name',
            'email',
            'tel_num',
            'address',
        ])->toArray();

        return $customer->update($dataCustomer);
    }
}
