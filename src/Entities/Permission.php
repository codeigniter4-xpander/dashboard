<?php

namespace CI4Xpander_Dashboard\Entities;

class Permission extends \CI4Xpander\Entity
{
    protected $attributes = [
        'code' => '',
        'name' => '',
        'description' => '',
        'status_id' => 0
    ];

    protected $casts = [
        'code' => 'string',
        'name' => 'string',
        'description' => 'string',
        'status_id' => 'integer'
    ];
}
