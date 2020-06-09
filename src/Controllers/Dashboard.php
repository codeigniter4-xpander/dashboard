<?php namespace CI4Xpander_Dashboard\Controllers;

use CI4Xpander\View\Data;
use CI4Xpander_AdminLTE\View\Component\Box;
use CI4Xpander_AdminLTE\View\Component\Column;
use CI4Xpander_AdminLTE\View\Component\Row;

/**
 * @property \CI4Xpander_Dashboard\View $view
 */
class Dashboard extends \CI4Xpander\Controller
{
    protected $isCRUD = false;

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
            $box = Box::create();
            $box->data->head->title = 'Daftar';
            $box->data->body = 'DAFTAR';

            $col = Column::create();
            $col->data->content = $box;

            $row = Row::create();
            $row->data->content = $col;

            $this->view->data->template->content = $row;
            return $this->view->render();
        });
    }
}
