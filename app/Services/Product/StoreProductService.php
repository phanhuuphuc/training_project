<?php

namespace App\Services\Product;

use App\Repositories\ProductRepository;
use Mi\L5Core\Services\BaseService;
use App\Common\Image;
use App\Common\LastProduct;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class StoreProductService extends BaseService
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
        $data = $this->data->all();

        if (isset($data['product_image'])) {
            $data['product_image'] = Image::generateStorageImage($data['product_image']);
        }

        $data['product_id'] = strtoupper(Str::limit(Str::slug($data['product_name']), 1, '')) . str_pad(LastProduct::getLastProductId(), 9, '0', STR_PAD_LEFT);
        $data['created_at'] = now();
        $data['updated_at'] = now();

        Session::flash('mgs_product', 'Thêm sản phẩm ' . $data['product_name'] . ' thành công!');

        return $this->repository->insert($data);
    }
}
