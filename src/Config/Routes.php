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

        $routes->match([
            'get', 'post'
        ], 'role-and-permission', 'Role_and_permission::index');

        $routes->match([
            'get', 'post'
        ], 'role-and-permission/create', 'Role_and_permission::create');

        $routes->match([
            'get', 'post'
        ], 'role-and-permission/update/(:num)', 'Role_and_permission::update/$1');

        $routes->match([
            'get', 'post'
        ], 'user', 'User::index');

        $routes->match([
            'get', 'post'
        ], 'user/create', 'User::create');

        $routes->match([
            'get', 'post'
        ], 'user/update/(:num)', 'User::update/$1');

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
