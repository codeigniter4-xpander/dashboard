<?php namespace CI4Xpander\Dashboard\Controllers;

/**
 * @property \CI4Xpander\Dashboard\View $view
 */
class Login extends \CI4Xpander\Controller
{
    public function index()
    {
        helper('form');

        return $this->_render(function () {
            return $this->view->render('Login');
        });
    }

    protected function _action_login()
    {
        if ($this->validate([
            'email' => 'required|valid_email',
            'password' => 'required'
        ])) {

        } else {
            \Config\Services::session()->setFlashdata('message', $this->validator->listErrors());
        }
    }
}
