<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseRequest;
use App\Http\Requests\Product\ListProductRequest;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Product\ProductCollection;
use App\Services\Product\DeleteProductService;
use App\Services\Product\ListProductService;
use App\Services\Product\StoreProductService;
use App\Services\Product\UpdateProductService;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListProductRequest $request)
    {
        if (isset($request->all()['axios'])) {
            $request->merge(['per_page' => BaseRequest::DEFAULT_PER_PAGE]);
            $products = resolve(ListProductService::class)->setData($request)->handle();
            return  new ProductCollection($products);
        }

        return Inertia::render('Product/List', [
            'mgs_product' => Session::get('mgs_product')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Product/Create');
    }

    /**
     * edit
     */
    public function edit(string $id)
    {
        $product = Product::find($id);
        return Inertia::render('Product/Edit', [
            'editProduct' =>  new ProductResource($product)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $respone = resolve(StoreProductService::class)->setRequest($request)->handle();
        return response($respone, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $respone = resolve(UpdateProductService::class)->setRequest($request)->setModel($id)->handle();
        return response($respone);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        resolve(DeleteProductService::class)->setModel($id)->handle();
        return response('success');
    }
}
