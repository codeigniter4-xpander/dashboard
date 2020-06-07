<?php

namespace CI4Xpander\Dashboard\Models;

class Process extends \CI4Xpander\Model
{
    protected $table = 'process';
    protected $allowedFields = [
        'code', 'name', 'description', 'status_id', 'property', 'type_id'
    ];
    protected $returnType = \CI4Xpander\Dashboard\Entities\Process::class;
}
