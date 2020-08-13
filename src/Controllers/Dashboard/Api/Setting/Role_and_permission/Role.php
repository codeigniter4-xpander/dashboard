<?php namespace CI4Xpander_Dashboard\Controllers\Dashboard\Api\Setting\Role_and_permission;

use CI4Xpander\Helpers\Input;

class Role extends \CI4Xpander_API\Controller
{
    protected $_name = 'Role';

    protected function _init()
    {
        parent::_init();

        $query = \Config\Database::connect()->table('role')
            ->select('role.*')
            ->select('role.name text');

        $input = Input::filter($this->request->getGet());
        if (array_key_exists('where_not', $input)) {
            $split = explode('.', $input['where_not']);
            $kolom = $split[0];
            $value = $split[1];
            $query->where("{$kolom} !=", $value);
        }

        $this->setCRUD([
            'enable' => true,
            'index' => [
                'query' => $query,
                'searchColumns' => [
                    'name',
                ],
            ],
        ]);
    }
}