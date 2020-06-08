<?php

namespace CI4Xpander_Dashboard\Entities;

class Permission extends \CI4Xpander\Entity
{
    protected $casts = [
        'code' => 'string',
        'name' => 'string',
        'description' => 'string',
        'status_id' => 'integer'
    ];
}
