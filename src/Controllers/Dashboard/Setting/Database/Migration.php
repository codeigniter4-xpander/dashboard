<?php namespace CI4Xpander_Dashboard\Controllers\Dashboard\Setting\Database;

class Migration extends \CI4Xpander_Dashboard\Controller
{
    protected $name = 'Migration';

    protected function _init()
    {
        parent::_init();

        $this->CRUD = [
            'enable' => true,
        ];
    }
}
