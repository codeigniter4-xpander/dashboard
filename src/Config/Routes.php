<?php namespace CI4Xpander_Dashboard\Config;

/** @var \CodeIgniter\Router\RouteCollection $routes */
$routes->match([
    'get'
], 'dashboard/logout', 'Logout::index', [
    'namespace' => 'CI4Xpander_Dashboard\Controllers'
]);

$routes->match([
    'get', 'post'
], 'login', 'Login::index', [
    'namespace' => 'CI4Xpander_Dashboard\Controllers',
    'filter' => 'ci4XpanderDashboardAuth:web,outside'
]);

$routes->group('dashboard', [
    'namespace' => 'CI4Xpander_Dashboard\Controllers',
    'filter' => 'ci4XpanderDashboardAuth:web,inside'
], function (\CodeIgniter\Router\RouteCollection $routes) {
    $routes->match([
        'get', 'post'
    ], '/', 'Dashboard::index');

    $routes->group('setting', [
        'namespace' => 'CI4Xpander_Dashboard\Controllers\Dashboard\Setting'
    ], function (\CodeIgniter\Router\RouteCollection $routes) {
        $routes->match([
            'get', 'post'
        ], 'site', 'Site::index');

        $routes->match([
            'get', 'post'
        ], 'role-and-permission', 'Role_and_permission::index');

        $routes->match([
            'get', 'post'
        ], 'user', 'User::index');

        $routes->group('database', [
            'namespace' => 'CI4Xpander_Dashboard\Controllers\Dashboard\Setting\Database'
        ], function (\CodeIgniter\Router\RouteCollection $routes) {
            $routes->match([
                'get', 'post'
            ], 'migration', 'Migration::index');
        });
    });
});
