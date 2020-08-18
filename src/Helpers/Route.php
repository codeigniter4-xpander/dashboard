<?php namespace CI4Xpander_Dashboard\Helpers;

class Route
{
    public static function create(\CodeIgniter\Router\RouteCollection $routes, $namespace = '')
    {
        $routes->get('/', "{$namespace}::index");
		$routes->get('data', "{$namespace}::data");
		$routes->match(['get', 'post'], 'create', "{$namespace}::create");
		$routes->match(['get', 'post'], 'update/(:num)', "{$namespace}::update/$1");
		$routes->delete('delete', "{$namespace}::delete");
    }
}