<?php namespace CI4Xpander_Dashboard\Controllers\Dashboard;

class Logout extends \CI4Xpander_Dashboard\Controller
{
    protected $name = 'Logout';

    public function index()
    {
        \Config\Services::session()->destroy();
        return redirect('login');
    }
}
