<?php

namespace CI4Xpander\Dashboard\Models\Process;

class Type extends \CI4Xpander\Model
{
    protected $table = 'process_type';
    protected $allowedFields = [
        'status_id', 'code', 'name', 'description'
    ];
    protected $returnType = \CI4Xpander\Dashboard\Entities\Process\Type::class;
}
