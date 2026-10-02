<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('registration', 'Registration::index');
$routes->get('registration/client', 'Registration::client');
$routes->post('registration/client', 'Registration::registerClient');
$routes->post('registration/update-password', 'Registration::updateEmployeePassword');
$routes->get('registration/employee', 'Registration::employee');
