<?php

namespace CI4Xpander\Dashboard\Models;

class Menu extends \CI4Xpander\Model
{
    protected $table = 'menu';
    protected $allowedFields = [
        'code', 'name', 'description', 'url', 'icon', 'level', 'parent_id', 'status_id', 'sequence_position', 'type_id'
    ];
    protected $returnType = \CI4Xpander\Dashboard\Entities\Menu::class;
}
