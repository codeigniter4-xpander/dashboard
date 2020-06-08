<?php

namespace CI4Xpander_Dashboard\Entities;

class Menu extends \CI4Xpander\Entity
{
    protected $casts = [
        'parent_id' => 'integer',
        'status_id' => 'integer',
        'type_id' => 'integer',
        'code' => 'string',
        'name' => 'string',
        'description' => 'string',
        'url' => 'string',
        'icon' => 'string',
        'level' => 'integer',
        'sequence_position' => 'integer'
    ];
}
