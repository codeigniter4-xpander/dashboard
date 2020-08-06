<?php namespace CI4Xpander_Dashboard\Controllers\Dashboard\Api\Setting\Role_and_permission;

class Role extends \CI4Xpander_API\Controller
{
    protected $_name = 'Role';

    protected function _init()
    {
        parent::_init();

        $this->setCRUD([
            'enable' => true,
            'index' => [
                'query' => \Config\Database::connect()->table('role')
                    ->select('role.*')
                    ->select('role.name text'),
                'searchColumns' => [
                    'name',
                ],
            ],
        ]);
    }
}