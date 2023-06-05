<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseRequest;
use App\Http\Requests\Customer\ListCustomerRequest;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Http\Resources\Customer\CustomerCollection;
use App\Services\Customer\DeleteCustomerService;
use App\Services\Customer\ListCustomerService;
use App\Services\Customer\StoreCustomerService;
use App\Services\Customer\UpdateCustomerService;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ImportCustomerRequest;
use App\Services\Customer\ExportCustomerService;
use App\Services\Customer\ImportCustomerService;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListCustomerRequest $request)
    {
        if ($request->get('axios')) {
            $request->merge(['per_page' => BaseRequest::DEFAULT_PER_PAGE]);
            $customers = resolve(ListCustomerService::class)->setData($request)->handle();
            return  new CustomerCollection($customers);
        }
        if ($request->get('export_csv')) {
            return resolve(ExportCustomerService::class)->setData($request)->handle();
        }

        return Inertia::render('Customer/List');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $respone = resolve(StoreCustomerService::class)->setRequest($request)->handle();
        return response($respone, 201);
    }

    /**
     * import a newly created resource in storage.
     */
    public function import(ImportCustomerRequest $request)
    {
        $respone = resolve(ImportCustomerService::class)->setRequest($request)->handle();
        return response($respone, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, string $id)
    {
        $respone = resolve(UpdateCustomerService::class)->setRequest($request)->setModel($id)->handle();
        return response($respone);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        resolve(DeleteCustomerService::class)->setModel($id)->handle();
        return response('success');
    }
}
