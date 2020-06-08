<?php

namespace CI4Xpander_Dashboard\Entities;

class User extends \CI4Xpander\Entity
{
    protected $casts = [
        'status_id' => 'integer',
        'code' => 'string',
        'email' => 'string',
        'name' => 'string',
        'password' => 'string',
    ];

    const SCHEMA = [
        'user_role' => [
            \CI4Xpander_Dashboard\Entities\User\Role::class,
            '$target' => 'user_id',
            'role' => [
                Role::class,
                '$source' => 'role_id',
                'role_permission' => [
                    \CI4Xpander_Dashboard\Entities\Role\Permission::class,
                    '$target' => 'role_id',
                    'permission' => [
                        Permission::class,
                        '$source' => 'permission_id',
                        'status' => [
                            Status::class,
                            '$source' => 'status_id',
                        ],
                    ],
                    'status' => [
                        Status::class,
                        '$source' => 'status_id',
                    ],
                ],
                'status' => [
                    Status::class,
                    '$source' => 'status_id',
                ],
            ],
            'status' => [
                Status::class,
                '$source' => 'status_id',
            ],
        ],
        'user_permission' => [
            \CI4Xpander_Dashboard\Entities\User\Permission::class,
            '$target' => 'user_id',
            'permission' => [
                Permission::class,
                '$source' => 'permission_id',
                'status' => [
                    Status::class,
                    '$source' => 'status_id'
                ]
            ],
            'status' => [
                Status::class,
                '$source' => 'status_id',
            ],
        ],
        'status' => [
            Status::class,
            '$source' => 'status_id',
        ],
    ];
}
