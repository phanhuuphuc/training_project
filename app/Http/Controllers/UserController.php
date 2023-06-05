<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseRequest;
use App\Http\Requests\User\BlockUserRequest;
use App\Http\Requests\User\ListUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserCollection;
use App\Services\User\BlockUserService;
use App\Services\User\DeleteUserService;
use App\Services\User\ListUserService;
use App\Services\User\StoreUserService;
use App\Services\User\UpdateUserService;
use Inertia\Inertia;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListUserRequest $request)
    {
        if (isset($request->all()['axios'])) {
            $request->merge(['per_page' => BaseRequest::DEFAULT_PER_PAGE]);
            $users = resolve(ListUserService::class)->setHandler($request->user())->setData($request)->handle();
            return  new UserCollection($users);
        }

        return Inertia::render('User/List');
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
    public function store(StoreUserRequest $request)
    {
        $respone = resolve(StoreUserService::class)->setRequest($request)->handle();
        return response($respone, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $respone = resolve(UpdateUserService::class)->setRequest($request)->setModel($id)->handle();
        return response($respone);
    }

    /**
     * Block the specified resource in storage.
     */
    public function block(BlockUserRequest $request, string $id)
    {
        resolve(BlockUserService::class)->setRequest($request)->setModel($id)->handle();
        return response('success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        resolve(DeleteUserService::class)->setModel($id)->handle();
        return response('success');
    }
}
