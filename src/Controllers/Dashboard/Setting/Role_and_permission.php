<?php namespace CI4Xpander_Dashboard\Controllers\Dashboard\Setting;

class Role_and_permission extends \CI4Xpander_Dashboard\Controller
{
    protected $name = 'Role & Permission';

    protected function _init()
    {
        parent::_init();

        $this->CRUD = [
            'enable' => true,
        ];
    }
}
