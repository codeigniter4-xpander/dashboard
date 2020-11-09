<?php namespace CI4Xpander_Dashboard\Config;

$routes->setDefaultNamespace('CI4Xpander_Dashboard\Controllers');

$routes->match([
    'get', 'post'
], 'login', 'Login::index', [
    'filter' => 'CI4XDashboardAuth:web,outside'
]);

$routes->group('dashboard', [
    'namespace' => 'Dashboard',
    'filter' => 'CI4XDashboardAuth:web,inside'
], function (\CI4Xpander\Core\RouteCollection $routes) {
    $routes->get('/', '::index');
    $routes->get('logout', 'Logout::index');

    $routes->group('setting', [
        'namespace' => 'Setting',
    ], function (\CI4Xpander\Core\RouteCollection $routes) {
        $routes->group('role-and-permission', [
            'namespace' => 'Setting\Role_and_permission',
        ], function (\CI4Xpander\Core\RouteCollection $routes) {
            \CI4Xpander_Dashboard\Helpers\Route::create($routes, 'Role', 'role');
            \CI4Xpander_Dashboard\Helpers\Route::create($routes, 'Permission', 'permission');
        });

        $routes->group('user', [
            'namespace' => 'Setting',
        ], function (\CI4Xpander\Core\RouteCollection $routes) {
            \CI4Xpander_Dashboard\Helpers\Route::create($routes, 'User');
        });

        $routes->group('database', [
            'namespace' => 'Setting\Database',
        ], function (\CI4Xpander\Core\RouteCollection $routes) {
            $routes->match([
                'get', 'post'
            ], 'migration', 'Migration::index');
        });
    });

    $routes->group('ajax', [
        'namespace' => 'Ajax',
    ], function (\CI4Xpander\Core\RouteCollection $routes) {
        $routes->group('setting', [
            'namespace' => 'Setting',
        ], function (\CI4Xpander\Core\RouteCollection $routes) {
            $routes->group('role-and-permission', [
                'namespace' => 'Role_and_permission',
            ], function (\CI4Xpander\Core\RouteCollection $routes) {
                $routes->get('permission', 'Permission::index');
                $routes->get('role', 'Role::index');
            });
        });
    });
});
