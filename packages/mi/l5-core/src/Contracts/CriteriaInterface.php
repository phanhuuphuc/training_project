<?php

namespace Mi\L5Core\Contracts;

use Mi\L5Core\Contracts\RepositoryInterface;

interface CriteriaInterface
{
    /**
     * Apply the criteria
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \Mi\L5Core\Contracts\RepositoryInterface $repository
     * @return void
     */
    public function apply($model, RepositoryInterface $repository);
}
