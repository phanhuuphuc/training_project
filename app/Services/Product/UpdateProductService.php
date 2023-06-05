<?php

namespace App\Services\Product;

use App\Repositories\ProductRepository;
use Mi\L5Core\Services\BaseService;
use App\Common\Image;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Session;

class UpdateProductService extends BaseService
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
        $product = Product::find($this->model);
        throw_if(!$product, new ModelNotFoundException());

        $data = $this->data->all();

        if (isset($data['product_image'])) {
            if ($product->product_image) {
                Image::deleteStorageImage($product->product_image);
            }
            $data['product_image'] = Image::generateStorageImage($data['product_image']);
        }

        Session::flash('mgs_product', 'Update sản phẩm ' . $data['product_name'] . ' thành công!');

        return $product->update($data);
    }
}
