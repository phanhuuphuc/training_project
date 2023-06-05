<?php

namespace App\Services\User;

use App\Enums\Status;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mi\L5Core\Services\BaseService;

class DeleteUserService extends BaseService
{
    protected $resvRepository;

    public function __construct(
        UserRepository $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * Logic to handle the data
     */
    public function handle()
    {
        $user = User::find($this->model);
        throw_if(!$user, new ModelNotFoundException());
        $user->is_delete = Status::YES;
        $user->save();
        return $user;
    }
}
