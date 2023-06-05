<?php

namespace App\Services\User;

use App\Criteria\UserNotDeleteCriteria;
use App\Filters\Email;
use App\Filters\GroupRole;
use App\Filters\IsActive;
use App\Filters\Name;
use App\Repositories\UserRepository;
use Mi\L5Core\Criteria\FilterCriteria;
use Mi\L5Core\Criteria\OrderCriteria;
use Mi\L5Core\Criteria\WithRelationsCriteria;
use Mi\L5Core\Services\BaseService;

class ListUserService extends BaseService
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
        $data = $this->repository->pushCriteria(new FilterCriteria($this->data->toArray(), $this->allowFilters()))
        ->pushCriteria(new WithRelationsCriteria($this->data->get('with'), $this->repository->allowRelations()))
        ->pushCriteria(new OrderCriteria($this->data->get('order', '-id')))
        ->pushCriteria(new UserNotDeleteCriteria());
        return $this->data->has('per_page')
            ? $data->paginate((int)$this->getPerPage())
            : $data->all();
    }

    private function allowFilters()
    {
        return [
            'name' => Name::class,
            'email' => Email::class,
            'group_role' => GroupRole::class,
            'is_active' => IsActive::class,
        ];
    }
}
