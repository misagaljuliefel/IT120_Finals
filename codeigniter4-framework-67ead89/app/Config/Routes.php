<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->get('login', 'Auth::index');
$routes->post('login', 'Auth::login');
$routes->get('dashboard', 'Dashboard::index');


$routes->get('registration', 'Registration::index');
$routes->get('registration/client', 'Registration::client');
$routes->post('registration/client', 'Registration::registerClient');
