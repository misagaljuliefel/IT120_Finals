    <?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->get('login', 'Auth::index');
$routes->post('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

$routes->get('dashboard', 'Dashboard::index');
$routes->get('admin', 'Dashboard::admin');

$routes->get('timein', 'TimeIn::index');
$routes->post('timein/save', 'TimeIn::save');

$routes->get('registration', 'Registration::index');
$routes->get('registration/client', 'Registration::client');
$routes->post('registration/client', 'Registration::registerClient');

$routes->get('admin/employees/create', 'AdminUsers::create');
$routes->post('admin/employees/store', 'AdminUsers::store');
$routes->get('admin/employees/edit/(:num)', 'AdminUsers::edit/$1');
$routes->post('admin/employees/update/(:num)', 'AdminUsers::update/$1');
$routes->post('admin/employees/status/(:num)', 'AdminUsers::toggleStatus/$1');
