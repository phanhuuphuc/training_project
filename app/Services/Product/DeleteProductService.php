<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mi\L5Core\Services\BaseService;

class DeleteProductService extends BaseService
{
    protected $resvRepository;

    public function __construct(
        ProductRepository $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * Logic to handle the data
     */
    public function handle()
    {
        $product = Product::find($this->model);
        throw_if(!$product, new ModelNotFoundException());
        return $product->delete();
    }
}
