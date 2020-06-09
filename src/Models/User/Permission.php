<?php

namespace CI4Xpander_Dashboard\Models\User;

class Permission extends \CI4Xpander\Model
{
    protected $table = 'user_permission';
    protected $allowedFields = [
        'status_id', 'user_id', 'permission_id', 'C', 'R', 'U', 'D'
    ];
    protected $returnType = \CI4Xpander_Dashboard\Entities\User\Permission::class;

    use \CI4Xpander\ModelFactoryTrait;
}
