<?php

namespace CI4Xpander\Dashboard\Entities\Menu;

class Type extends \CI4Xpander\Entity
{
    protected $casts = [
        'status_id' => 'integer',
        'code' => 'string',
        'name' => 'string',
        'description' => 'string'
    ];
}
