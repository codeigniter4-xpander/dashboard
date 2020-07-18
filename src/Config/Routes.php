<?php namespace CI4Xpander_Dashboard\Config;

/** @var \CodeIgniter\Router\RouteCollection $routes */
$routes->match([
    'get'
], 'dashboard/logout', 'Logout::index', [
    'namespace' => 'CI4Xpander_Dashboard\Controllers',
    'filter' => 'CI4XDashboardAuth:web,inside'
]);

$routes->match([
    'get', 'post'
], 'login', 'Login::index', [
    'namespace' => 'CI4Xpander_Dashboard\Controllers',
    'filter' => 'CI4XDashboardAuth:web,outside'
]);

$routes->group('dashboard', [
    'namespace' => 'CI4Xpander_Dashboard\Controllers',
    'filter' => 'CI4XDashboardAuth:web,inside'
], function (\CodeIgniter\Router\RouteCollection $routes) {
    $routes->match([
        'get', 'post'
    ], '/', 'Dashboard::index');

    $routes->group('setting', [
        'namespace' => 'CI4Xpander_Dashboard\Controllers\Dashboard\Setting',
        'filter' => 'CI4XDashboardAuth:web,inside'
    ], function (\CodeIgniter\Router\RouteCollection $routes) {
        $routes->match([
            'get', 'post'
        ], 'site', 'Site::index');

        $routes->match([
            'get', 'post'
        ], 'site/update/(:num)', 'Site::update/$1');

        $routes->group('role-and-permission', [
            'namespace' => 'CI4Xpander_Dashboard\Controllers\Dashboard\Setting\Role_and_permission',
            'filter' => 'CI4XDashboardAuth:web,inside'
        ], function (\CodeIgniter\Router\RouteCollection $routes) {
            $routes->get('role', 'Role::index');
            $routes->get('role/data', 'Role::data');
        });

        $routes->group('user', [
            'namespace' => 'CI4Xpander_Dashboard\Controllers\Dashboard\Setting',
            'filter' => 'CI4XDashboardAuth:web,inside'
        ], function (\CodeIgniter\Router\RouteCollection $routes) {
            $routes->get('/', 'User::index');
            $routes->get('data', 'User::data');
        });

        $routes->group('database', [
            'namespace' => 'CI4Xpander_Dashboard\Controllers\Dashboard\Setting\Database',
            'filter' => 'CI4XDashboardAuth:web,inside'
        ], function (\CodeIgniter\Router\RouteCollection $routes) {
            $routes->match([
                'get', 'post'
            ], 'migration', 'Migration::index');
        });
    });
});
