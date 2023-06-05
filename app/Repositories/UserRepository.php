<?php

namespace App\Repositories;

use App\Models\User;
use Mi\L5Core\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
    /**
     * Get the model of repository
     *
     * @return string
     */
    public function getModel()
    {
        return User::class;
    }

    public function allowRelations()
    {
        return [

        ];
    }

    public function getOrderableFields()
    {
        return [
            'id'
        ];
    }
}
