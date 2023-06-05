<?php

namespace App\Services\User;

use App\Repositories\UserRepository;
use Mi\L5Core\Services\BaseService;

class StoreUserService extends BaseService
{
    protected $collectsData = true;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Logic to handle the data
     */
    public function handle()
    {
        $dataUser = $this->data->only([
            'name',
            'email',
            'password',
            'is_active',
            'group_role',
        ])->toArray();
        return $this->repository->create($dataUser);
    }
}
