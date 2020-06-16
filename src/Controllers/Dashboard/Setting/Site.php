<?php namespace CI4Xpander_Dashboard\Controllers\Dashboard\Setting;

class Site extends \CI4Xpander_Dashboard\Controller
{
    protected $name = 'Site';

    protected function _init()
    {
        parent::_init();

        $this->CRUD = [
            'enable' => true,
        ];
    }
}
