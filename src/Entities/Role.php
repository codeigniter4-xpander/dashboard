<?php

namespace CI4Xpander_Dashboard\Entities;

class Role extends \CI4Xpander\Entity
{
    protected $casts = [
        'code' => 'string',
        'name' => 'string',
        'description' => 'string',
        'status_id' => 'integer',
        'level' => 'integer',
        'parent_id' => 'integer'
    ];
}
