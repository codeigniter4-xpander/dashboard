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
            $user = \CI4Xpander_Dashboard\Models\User::create()
                ->select('user.*')
                ->join('user_role ur', 'ur.user_id = user.id')
                ->join('role r', 'r.id = ur.role_id')
                ->join('role_permission rp', 'rp.role_id = r.id')
                ->join('permission p', 'p.id = rp.permission_id')
                ->where('email', $this->request->getPost('email'))
                ->where('p.code', 'login')
                ->first();
            
            if (!is_null($user)) {
                $verifyPassword = password_verify($this->request->getPost('password'), $user->password);

                if ($verifyPassword) {
                    unset($user->password);
                    \Config\Services::session()->set('user', $user);
                    return redirect('dashboard');
                }
            }

            \Config\Services::session()->setFlashdata('message', 'Account not found or your password is wrong');
        } else {
            \Config\Services::session()->setFlashdata('message', $this->validator->listErrors());
        }
    }
}
