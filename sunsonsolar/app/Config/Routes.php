<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Auth::index');

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