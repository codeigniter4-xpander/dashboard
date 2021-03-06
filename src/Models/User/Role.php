<?php

namespace CI4Xpander\Dashboard\Models\User;

class Role extends \CI4Xpander\Model
{
    protected $table = 'user_role';
    protected $allowedFields = [
        'status_id', 'user_id', 'role_id'
    ];
    protected $returnType = \CI4Xpander\Dashboard\Entities\User\Role::class;
}
