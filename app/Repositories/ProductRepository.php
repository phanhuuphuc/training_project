<?php

namespace App\Repositories;

use App\Models\Product;
use Mi\L5Core\Repositories\BaseRepository;

class ProductRepository extends BaseRepository
{
    /**
     * Get the model of repository
     *
     * @return string
     */
    public function getModel()
    {
        return Product::class;
    }

    public function allowRelations()
    {
        return [

        ];
    }

    public function getOrderableFields()
    {
        return [
            'created_at'
        ];
    }
}
