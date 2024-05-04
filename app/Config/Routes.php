<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Login::index');
$routes->get('/info', 'Login::info');
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/register', 'Register::index');
$routes->post('/auth/register', 'Auth::register');
$routes->get('/login', 'Login::index');
$routes->post('/auth/login', 'Auth::login');
$routes->get('/auth/logout', 'Auth::logout');
$routes->get('/api/c_seeder/(:any)', 'Api::c_seeder/$1');
$routes->post('/api/add_contact', 'Api::add_contact',);
$routes->post('/api/get_last_contact', 'Api::get_last_contact');
$routes->get('/api/delete_contact', 'Api::delete_contact');
$routes->get('/api/get_contact_for_edit', 'Api::get_contact_for_edit');
$routes->post('/api/update_contact', 'Api::update_contact');
$routes->get('/api/update_contact_view', 'Api::update_contact_view');



