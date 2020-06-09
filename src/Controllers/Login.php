<?php namespace CI4Xpander_Dashboard\Controllers;

/**
 * @property \CI4Xpander_Dashboard\View $view
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
            $user = \CI4Xpander_Dashboard\Models\User::create()->withScheme()->where('email', $this->request->getPost('email'))->getCompiledSelect();

            d($user);
        } else {
            \Config\Services::session()->setFlashdata('message', $this->validator->listErrors());
        }
    }
}
