<?php namespace CI4Xpander_Dashboard\Controllers\Dashboard\Setting;

class User extends \CI4Xpander_Dashboard\Controller
{
    protected $name = 'User';

    protected function _init()
    {
        parent::_init();

        $this->CRUD = [
            'enable' => true,
        ];
    }
}
