<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */

    public function toArray($request)
    {
        $result = $this->resource->only([
            'id',
            'name',
            'email',
            'password',
            'email_verified_at',
            'is_active',
            'group_role',
            'last_login_at',
            'last_login_ip',
            'deleted_at',
            'created_at',
            'updated_at',
        ]);

        return $result;
    }
}
