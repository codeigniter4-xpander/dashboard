<?php namespace CI4Xpander_Dashboard\Config;

class Services extends \CodeIgniter\Config\BaseService
{
    public static function dashboardMessage($type = null, $value = null, bool $shared = true)
    {
        if ($shared) {
            return static::getSharedInstance('dashboardMessage', $type, $value);
        }

        return \CI4Xpander_Dashboard\Helpers\Message::create($type, $value);
    }
}