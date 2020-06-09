<?php

namespace CI4Xpander_Dashboard\Models;

class Permission extends \CI4Xpander\Model
{
    protected $table = 'permission';
    protected $allowedFields = [
        'code', 'name', 'description', 'status_id'
    ];
    protected $returnType = \CI4Xpander_Dashboard\Entities\Permission::class;

    use \CI4Xpander\ModelFactoryTrait;
}
