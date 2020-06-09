<?php

namespace CI4Xpander_Dashboard\Models\Menu;

class Permission extends \CI4Xpander\Model
{
    protected $table = 'menu_permission';
    protected $allowedFields = [
        'status_id', 'menu_id', 'permission_id', 'C', 'R', 'U', 'D'
    ];
    protected $returnType = \CI4Xpander_Dashboard\Entities\Menu\Permission::class;

    use \CI4Xpander\ModelFactoryTrait;
}
