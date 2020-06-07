<?php

namespace CI4Xpander\Dashboard\Entities;

class Status extends \CI4Xpander\Entity
{
    protected $casts = [
        'code' => 'string',
        'name' => 'string',
        'description' => 'string'
    ];
}
