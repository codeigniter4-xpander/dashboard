<?php namespace CI4Xpander_Dashboard\Helpers;

class Route
{
    public static function create(\CodeIgniter\Router\RouteCollection $routes, $namespace = '', $url = null)
    {


        $routes->get(isset($url) ? $url : '/', "{$namespace}::index");
		$routes->get((isset($url) ? $url . '/' : '') . 'data', "{$namespace}::data");
		$routes->match(['get', 'post'], (isset($url) ? $url . '/' : '') . 'create', "{$namespace}::create");
		$routes->match(['get', 'put'], (isset($url) ? $url . '/' : '') . 'update/(:num)', "{$namespace}::update/$1");
		$routes->delete((isset($url) ? $url . '/' : '') . 'delete', "{$namespace}::delete");
    }
}
