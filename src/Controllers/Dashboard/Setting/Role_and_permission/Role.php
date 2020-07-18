<?php namespace CI4Xpander_Dashboard\Controllers\Dashboard\Setting\ROle_and_permission;

use \CI4Xpander\Helpers\Database\Builder;

class Role extends \CI4Xpander_Dashboard\Controller
{
    protected $name = 'Role';

    protected function _init()
    {
        parent::_init();

        $this->CRUD = [
            'enable' => true,
            'base_url' => base_url('dashboard/setting/role-and-permission'),
            'permission' => 'dashboardSettingRole',
            'index' => [
                'isDataTable' => true,
                'isServerSide' => true,
                'isMapResultServerSide' => true,
                'query' => \Config\Database::connect()->table('role')
                    ->select('role.*')
                    ->select("(" .
                        \Config\Database::connect()->table('permission')
                            ->select("ARRAY_TO_JSON(ARRAY_AGG(" . Builder::protect('permission.name') . "))", false)
                            ->join('role_permission', 'role_permission.permission_id = permission.id')
                            ->join('status permission_status', 'permission_status.id = permission.status_id')
                            ->join('status role_permission_status', 'role_permission_status.id = role_permission.status_id')
                            ->where('role_permission.role_id = role.id')
                            ->where('permission.deleted_at', null)
                            ->where('role_permission.deleted_at', null)
                            ->where('permission_status.code', 'active')
                            ->where('role_permission_status.code', 'active')
                            ->getCompiledSelect()
                    . ") permissions", false)
                    ->join('status role_status', 'role_status.id = role.status_id')
                    ->where('role.deleted_at', null)
                    ->where('role_status.code', 'active'),
                'columns' => [
                    'code' => 'Code',
                    'name' => 'Name',
                    'level' => 'Level',
                    'permissions' => [
                        'label' => 'Permissions',
                        'value' => function ($value, $data) {
                            if (is_null($value) || empty($value)) {
                                return '';
                            }

                            return implode(', ', json_decode($value));
                        }
                    ]
                ]
            ]
        ];
    }
}
