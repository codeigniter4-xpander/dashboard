<?php namespace CI4Xpander_Dashboard\Controllers;

/**
 * @property \CI4Xpander_Dashboard\View $view
 */
class Dashboard extends \CI4Xpander\Controller
{
    protected function _init()
    {
        $this->view->data->user->name = \Config\Services::session()->get('user');
        $this->view->data->template->menu->items[] = new \CI4Xpander_AdminLTE\View\Component\Menu\Item\Data([
            'name' => 'Dasboard',
            'url' => 'dashboard',
            'isActive' => true,
            'icon' => 'fa fa-dashboard'
        ]);
    }

    public function index()
    {
        return $this->_render(function () {
            $box = \CI4Xpander_AdminLTE\View\Component\Box::create();
            $box->data->header->title = 'BOX';

            $this->view->data->template->content = $box->render();

            return $this->view->render();
        });
    }
}
