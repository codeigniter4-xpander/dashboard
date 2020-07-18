<?php namespace CI4Xpander_Dashboard\Controllers\Dashboard\Api\Setting\Role_and_permission;

class Permission extends \CI4Xpander_API\Controller
{
    protected $_name = 'Permission';

    protected function _init()
    {
        parent::_init();

        $this->setCRUD([
            'enable' => true,
            'index' => [
                'query' => \Config\Database::connect()->table('permission')
                    ->select('permission.*')
                    ->select('permission.name text'),
                'searchColumns' => [
                    'name',
                ],
            ],
        ]);
    }
}