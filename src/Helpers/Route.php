<?php namespace CI4Xpander_Dashboard\Helpers;

class Route
{
    public static function create(\CI4Xpander\Core\RouteCollection $routes, $config = [])
    {
        $namespace = $config['namespace'] ?? '';
        $url = $config['url'] ?? '';

        $options = [];
        if (isset($config['version'])) {
            $options['version'] = $config['version'];
        }

        $routes->get($url, "{$namespace}::index");
        // $routes->get(empty($url) ? 'data' : $url . '/data', "{$namespace}::data");
        $routes->match(['get', 'post'], empty($url) ? 'create' : $url . '/create', "{$namespace}::create");
        $routes->match(['get', 'put'], empty($url) ? 'update/(:num)' : $url . '/update/(:num)', "{$namespace}::update/$1");
        $routes->delete(empty($url) ? 'delete/(:num)' : $url . '/delete/(:num)', "{$namespace}::delete/$1");
    }
}
