<?php

namespace CI4Xpander_Dashboard\Entities\User;

class Permission extends \CI4Xpander\Entity
{
    protected $casts = [
        'status_id' => 'integer',
        'user_id' => 'integer',
        'permission_id' => 'integer',
        'C' => 'boolean',
        'R' => 'boolean',
        'U' => 'boolean',
        'D' => 'boolean'
    ];

    protected $datamap = [
        'C' => 'isCreate',
        'R' => 'isRead',
        'U' => 'isUpdate',
        'D' => 'isDelete'
    ];
}
