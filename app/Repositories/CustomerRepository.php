<?php

namespace App\Repositories;

use App\Models\Customer;
use Mi\L5Core\Repositories\BaseRepository;

class CustomerRepository extends BaseRepository
{
    /**
     * Get the model of repository
     *
     * @return string
     */
    public function getModel()
    {
        return Customer::class;
    }

    public function allowRelations()
    {
        return [

        ];
    }

    public function getOrderableFields()
    {
        return [
            'customer_id'
        ];
    }
}
