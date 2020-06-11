<?php namespace CI4Xpander_Dashboard\Controllers;

class Dashboard extends \CI4Xpander_Dashboard\Controller
{
    protected $name = 'Dashboard';

    public function index()
    {
        return $this->_render(function () {
            $this->view->data->page->title = 'Dashboard';

            return $this->view->render();
        });
    }
}
