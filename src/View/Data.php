<?php namespace CI4Xpander\Dashboard\View;

class Data extends \CI4Xpander\AdminLTE\View\Data
{
    protected function _init()
    {
        $this->site->title = 'CodeIgniter 4 Xpander | Dashboard';
        $this->site->name = 'CI4 Xpander';

        $this->page->title = 'Dashboard';
    }
}
