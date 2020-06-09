<?php

namespace CI4Xpander_Dashboard\Models;

class Status extends \CI4Xpander\Model
{
    protected $table = 'status';
    protected $allowedFields = [
        'code', 'name', 'description'
    ];
    protected $returnType = \CI4Xpander_Dashboard\Entities\Status::class;

    use \CI4Xpander\ModelFactoryTrait;
}
