<?php

namespace App\Criteria;

use App\Enums\Status;
use Mi\L5Core\Contracts\CriteriaInterface;
use Mi\L5Core\Contracts\RepositoryInterface;

class UserNotDeleteCriteria implements CriteriaInterface
{

    public function __construct()
    {
    }

    /**
     * Apply criteria in query repository
     *
     * @param RepositoryInterface $repository
     *
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('is_delete', Status::NO);
    }
}
