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
        $routes->group('role-and-permission', [
            'namespace' => 'CI4Xpander_Dashboard\Controllers\Dashboard\Setting\Role_and_permission',
            'filter' => 'CI4XDashboardAuth:web,inside'
        ], function (\CodeIgniter\Router\RouteCollection $routes) {
            $routes->get('role', 'Role::index');
            $routes->get('role/data', 'Role::data');
            $routes->match(['get', 'post'], 'role/create', 'Role::create');

            $routes->get('permission', 'Permission::index');
            $routes->get('permission/data', 'Permission::data');
            $routes->match(['get', 'post'], 'permission/create', 'Permission::create');
        });

        $routes->group('user', [
            'namespace' => 'CI4Xpander_Dashboard\Controllers\Dashboard\Setting',
            'filter' => 'CI4XDashboardAuth:web,inside'
        ], function (\CodeIgniter\Router\RouteCollection $routes) {
            $routes->get('/', 'User::index');
            $routes->get('data', 'User::data');
            $routes->match(['get', 'post'], 'create', 'User::create');
            $routes->match(['get', 'post'], 'update/(:num)', 'User:update/$1');
            $routes->get('delete/(:num)', 'User:delete/$1');
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

    $routes->group('api', [
        'namespace' => 'CI4Xpander_Dashboard\Controllers\Dashboard\Api',
        'filter' => 'CI4XDashboardAuth:web,inside'
    ], function (\CodeIgniter\Router\RouteCollection $routes) {
        $routes->group('setting', [
            'namespace' => 'CI4Xpander_Dashboard\Controllers\Dashboard\Api\Setting',
            'filter' => 'CI4XDashboardAuth:web,inside'
        ], function (\CodeIgniter\Router\RouteCollection $routes) {
            $routes->group('role-and-permission', [
                'namespace' => 'CI4Xpander_Dashboard\Controllers\Dashboard\Api\Setting\Role_and_permission',
                'filter' => 'CI4XDashboardAuth:web,inside'
            ], function (\CodeIgniter\Router\RouteCollection $routes) {
                $routes->get('permission', 'Permission::index');
                $routes->get('role', 'Role::index');
            });
        });
    });
});
