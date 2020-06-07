<?php

namespace CI4Xpander\Dashboard\Models;

class Status extends \CI4Xpander\Model
{
    protected $table = 'status';
    protected $allowedFields = [
        'code', 'name', 'description'
    ];
    protected $returnType = \CI4Xpander\Dashboard\Entities\Status::class;
}
