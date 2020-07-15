<?php namespace CI4Xpander_Dashboard\Controllers\Dashboard\Setting;

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
                'query' => \Config\Database::connect()->table('user u')
                    ->join('status s1', 's1.id = u.status_id')
                    ->where('u.deleted_at', null)
                    ->where('s1.code', 'active'),
                'columns' => [
                    'name' => 'Name',
                    'email' => 'Email'
                ],
            ],
        ];
    }
}
