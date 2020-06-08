<?php

namespace CI4Xpander_Dashboard\Entities;

class Process extends \CI4Xpander\Entity
{
    protected $casts = [
        'status_id' => 'integer',
        'type_id' => 'integer',
        'code' => 'string',
        'name' => 'string',
        'description' => 'string',
        'property' => 'json'
    ];
}
