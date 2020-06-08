<?php namespace CI4Xpander_Dashboard\Controllers;

class Logout extends \CI4Xpander\Controller
{
    public function index()
    {
        \Config\Services::session()->destroy();
        return redirect('login');
    }
}
