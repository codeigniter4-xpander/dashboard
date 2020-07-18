<?php namespace CI4Xpander_Dashboard\Controllers\Dashboard\Setting;

use \CI4Xpander\Helpers\Database\Builder;

class User extends \CI4Xpander_Dashboard\Controller
{
    protected $name = 'User';

    protected function _init()
    {
        parent::_init();

        $this->CRUD = [
            'enable' => true,
            'base_url' => base_url('dashboard/setting/user'),
            'permission' => 'dashboardSettingUser',
            'index' => [
                'isDataTable' => true,
                'isServerSide' => true,
                'isMapResultServerSide' => true,
                'query' => \Config\Database::connect()->table('user')
                    ->select('user.*')
                    ->select("(
                        SELECT ARRAY_TO_JSON(ARRAY_AGG(" . Builder::protect('role.name') . "))
                        FROM " . Builder::protect('role') . "
                        JOIN " . Builder::protect('user_role') . " ON " . Builder::protect('user_role.role_id') . " = " . Builder::protect('role.id') . "
                        WHERE " . Builder::protect('user_role.user_id') . " = " . Builder::protect('user.id') . "
                    ) " . Builder::protect('roles'), false)
                    ->join('status user_status', 'user_status.id = user.status_id')
                    ->where('user.deleted_at', null)
                    ->where('user_status.code', 'active'),
                'columns' => [
                    'code' => 'Code',
                    'name' => 'Name',
                    'email' => 'Email',
                    'roles' => [
                        'label' => 'Roles',
                        'value' => function ($value, $row) {
                            $value = json_decode($value);
                            $view = '<ul>';
                            foreach ($value as $role) {
                                $view .= "<li>{$role}</li>";
                            }
                            return $view . '</ul>';
                        }
                    ]
                ],
            ],
        ];
    }
}
