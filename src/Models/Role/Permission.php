<?php

namespace CI4Xpander\Dashboard\Models\Role;

class Permission extends \CI4Xpander\Model
{
    protected $table = 'permission';
    protected $allowedFields = [
        'role_id', 'permission_id', 'status_id', 'C', 'R', 'U', 'D'
    ];
    protected $returnType = \CI4Xpander\Dashboard\Entities\Role\Permission::class;
}
