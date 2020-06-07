<?php

namespace CI4Xpander\Dashboard\Models;

class Role extends \CI4Xpander\Model
{
    protected $table = 'role';
    protected $allowedFields = [
        'code', 'name', 'description', 'status_id', 'level', 'parent_id'
    ];
    protected $returnType = \CI4Xpander\Dashboard\Entities\Role::class;
}
