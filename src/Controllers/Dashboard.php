<?php namespace CI4Xpander\Dashboard\Controllers;

class Dashboard extends \CI4Xpander\Controller
{
    protected function _init()
    {
        $this->view->data->user->name = \Config\Services::session()->get('user');
    }

    public function index()
    {
        return $this->_render(function () {
            $card = \CI4Xpander\AdminLTE\View\Component\Card::create();
            $card->data->title = 'CARD';

            $this->view->data->template->content = $card->render();

            return $this->view->render();
        });
    }
}
