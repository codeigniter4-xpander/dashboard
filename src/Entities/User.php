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

    public function getRoles()
    {
        return \CI4Xpander_Dashboard\Models\Role::create()
            ->select('role.*')
            ->join('user_role ur', 'ur.role_id = role.id')
            ->where('ur.user_id', $this->id)
            ->findAll();
    }

    // public function getPermission($code = null, $action = null, $result = true)
    // {
    //     $userPermission = \CI4Xpander_Dashboard\Models\Permission::create()
    //         ->select('permission.*')
    //         ->join('user_permission up', 'up.permission_id = permission.id')
    //         ->where('up.user_id', $this->id);

    //     $rolePermission = \CI4Xpander_Dashboard\Models\Permission::create()
    //         ->select('permission.*')
    //         ->join('role_permission rp', 'rp.permission_id = permission.id')
    //         ->join('role r', 'r.id = rp.role_id')
    //         ->join('user_role ur', 'ur.role_id = r.id')
    //         ->where('ur.user_id', $this->id);

    //     if (!is_null($code)) {
    //         if (is_string($code)) {
    //             $userPermission->where('permission.code', $code);
    //             $rolePermission->where('permission.code', $code);

    //             if (!is_null($action)) {
    //                 if (is_string($action)) {
    //                     $userPermission->where("up.{$action}", true);
    //                     $rolePermission->where("up.{$action}", true);
    //                 } elseif (is_array($action)) {
    //                     foreach ($action as $a) {
    //                         $userPermission->where("up.{$a}", true);
    //                         $rolePermission->where("up.{$a}", true);
    //                     }
    //                 }
    //             }
    //         } elseif (is_array($code)) {
    //         }
    //     }

    //     if ($result) {
    //         return \Config\Database::connect()->table('q')
    //             ->from("({$userPermission->getCompiledSelect()} UNION {$rolePermission->getCompiledSelect()}) q", true)
    //             ->get()->getCustomResultObject(\CI4Xpander_Dashboard\Entities\Permission::class);
    //     } else {

    //     }

    //     return null;
    // }

    public function hasPermission($code = null, $action = null)
    {
        if (!is_null($code)) {
            if (is_string($code)) {
                $userPermission = \CI4Xpander_Dashboard\Models\Permission::create()
                    ->select('permission.*')
                    ->join('user_permission up', 'up.permission_id = permission.id')
                    ->where('up.user_id', $this->id)
                    ->where('permission.code', $code);

                $rolePermission = \CI4Xpander_Dashboard\Models\Permission::create()
                    ->select('permission.*')
                    ->join('role_permission rp', 'rp.permission_id = permission.id')
                    ->join('role r', 'r.id = rp.role_id')
                    ->join('user_role ur', 'ur.role_id = r.id')
                    ->where('ur.user_id', $this->id)
                    ->where('permission.code', $code);

                if (!is_null($action)) {
                    if (is_string($action)) {
                        $userPermission->where("up.{$action}", true);
                        $rolePermission->where("rp.{$action}", true);
                    } elseif (is_array($action)) {
                        foreach ($action as $a) {
                            $userPermission->where("up.{$a}", true);
                            $rolePermission->where("rp.{$a}", true);
                        }
                    }
                }

                $count = \Config\Database::connect()->table('ci4x_dashboard_entity_user_temporary_table')
                    ->from("({$userPermission->getCompiledSelect()} UNION {$rolePermission->getCompiledSelect()}) ci4x_dashboard_entity_user_temporary_table", true)
                    ->countAllResults();

                if ($count > 0) {
                    return true;
                } else {
                    return false;
                }
            } elseif (is_array($code)) {

            }
        }

        return false;
    }

    public function getPermission($code = null, $action = null, $result = true)
    {
        $resultClass = \CI4Xpander_Dashboard\Entities\Permission::class;
        $isRow = false;

        $userPermission = \CI4Xpander_Dashboard\Models\Permission::create()
            ->select('permission.*')
            ->join('user_permission up', 'up.permission_id = permission.id')
            ->where('up.user_id', $this->id);

        $rolePermission = \CI4Xpander_Dashboard\Models\Permission::create()
            ->select('permission.*')
            ->join('role_permission rp', 'rp.permission_id = permission.id')
            ->join('role r', 'r.id = rp.role_id')
            ->join('user_role ur', 'ur.role_id = r.id')
            ->where('ur.user_id', $this->id);

        if (!is_null($code)) {
            if (is_string($code)) {
                $isRow = true;

                $userPermission->where('permission.code', $code);
                $rolePermission->where('permission.code', $code);

                if (!is_null($action)) {
                    $resultClass = \CI4Xpander_Dashboard\Entities\Permission\Action::class;

                    if (is_string($action)) {
                        $userPermission->select("up.{$action}");
                        $userPermission->where("up.{$action}", true);
                        $rolePermission->select("rp.{$action}");
                        $rolePermission->where("rp.{$action}", true);
                    } elseif (is_array($action)) {
                        foreach ($action as $a) {
                            $userPermission->select("up.{$a}");
                            $userPermission->where("up.{$a}", true);
                            $rolePermission->select("rp.{$a}");
                            $rolePermission->where("rp.{$a}", true);
                        }
                    }
                }

                $permissionResult = \Config\Database::connect()->table('ci4x_dashboard_entity_user_temporary_table')
                    ->from("({$userPermission->getCompiledSelect()} UNION {$rolePermission->getCompiledSelect()}) ci4x_dashboard_entity_user_temporary_table", true);
            } elseif (is_array($code)) {

            }
        }

        if ($result) {
            if ($isRow) {
                return $permissionResult->get()->getCustomRowObject(0, $resultClass);
            } else {
                return $permissionResult->get()->getCustomResultObject($resultClass);
            }
        } else {
            return $permissionResult;
        }
    }
}
