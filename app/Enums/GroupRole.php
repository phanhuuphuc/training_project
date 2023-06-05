<?php

namespace App\Enums;

class GroupRole
{
    const ADMIN = 'Admin';
    const REVIEWER = 'Reviewer';
    const EDITOR = 'Editor';

    public static function getGroupRole()
    {
        return [
            self::ADMIN,
            self::REVIEWER,
            self::EDITOR,
        ];
    }
}
