<?php

namespace CI4Xpander_Dashboard\Models\Menu;

class Type extends \CI4Xpander\Model
{
    protected $table = 'menu_type';
    protected $allowedFields = [
        'status_id', 'code', 'name', 'description'
    ];
    protected $returnType = \CI4Xpander_Dashboard\Entities\Menu\Type::class;
}
