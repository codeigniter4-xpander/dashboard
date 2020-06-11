<?php namespace CI4Xpander_Dashboard\View;

class Data extends \CI4Xpander_AdminLTE\View\Data
{
    protected function _init()
    {
        parent::_init();

        $this->site->title = env('ci4xpander.site.title', '');
        $this->site->name = env('ci4xpander.site.name', '');
        $this->site->logo['mini'] = env('ci4xpander.site.logo.mini', '');
        $this->site->logo['large'] = env('ci4xpander.site.logo.large', '');
    }
}
