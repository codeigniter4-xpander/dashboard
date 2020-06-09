<?php

namespace CI4Xpander_Dashboard\Models\Process;

class Type extends \CI4Xpander\Model
{
    protected $table = 'process_type';
    protected $allowedFields = [
        'status_id', 'code', 'name', 'description'
    ];
    protected $returnType = \CI4Xpander_Dashboard\Entities\Process\Type::class;

    use \CI4Xpander\ModelFactoryTrait;
}
