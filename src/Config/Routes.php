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
});
