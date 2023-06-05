<?php

namespace App\Services\User;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mi\L5Core\Services\BaseService;

class UpdateUserService extends BaseService
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
        $user = User::find($this->model);
        throw_if(!$user, new ModelNotFoundException());

        $dataUser = $this->data->only([
            'name',
            'email',
            'is_active',
            'group_role',
        ])->toArray();

        if ($this->data->get('password')) {
            $dataUser['password'] = $this->data->get('password');
        }

        return $user->update($dataUser);
    }
}
