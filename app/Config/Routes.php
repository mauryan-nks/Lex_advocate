<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Site::index');
$routes->get('home', 'Site::index');

// Authentication / portals
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::authenticate');
$routes->get('logout', 'Auth::logout');
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

// Client invoices
$routes->get('my-invoices', 'Invoice::userIndex', ['filter' => 'auth']);
$routes->get('my-invoices/(:num)', 'Invoice::userShow/$1', ['filter' => 'auth']);
$routes->get('my-invoices/(:num)/print', 'Invoice::userPrint/$1', ['filter' => 'auth']);

// Admin
$routes->get('admin', 'Admin::index', ['filter' => 'admin']);

// Admin invoicing — these routes fix the 404 on invoice links.
$routes->get('admin/invoices', 'Invoice::index', ['filter' => 'admin']);
$routes->get('admin/invoices/create', 'Invoice::create', ['filter' => 'admin']);
$routes->post('admin/invoices/store', 'Invoice::store', ['filter' => 'admin']);
$routes->get('admin/invoices/(:num)', 'Invoice::show/$1', ['filter' => 'admin']);
$routes->post('admin/invoices/(:num)/payment', 'Invoice::addPayment/$1', ['filter' => 'admin']);
$routes->get('admin/invoices/(:num)/print', 'Invoice::print/$1', ['filter' => 'admin']);
$routes->get('admin/invoices/(:num)/pdf', 'Invoice::pdf/$1', ['filter' => 'admin']);

// Admin CRM
$routes->get('admin/crm', 'Crm::index', ['filter' => 'admin']);
$routes->get('admin/crm/clients', 'Crm::clients', ['filter' => 'admin']);
$routes->get('admin/crm/clients/create', 'Crm::createClient', ['filter' => 'admin']);
$routes->post('admin/crm/clients/create', 'Crm::storeClient', ['filter' => 'admin']);
$routes->get('admin/crm/clients/(:num)/edit', 'Crm::editClient/$1', ['filter' => 'admin']);
$routes->post('admin/crm/clients/(:num)/edit', 'Crm::updateClient/$1', ['filter' => 'admin']);
$routes->get('admin/crm/clients/(:num)/delete', 'Crm::deleteClient/$1', ['filter' => 'admin']);
$routes->get('admin/crm/cases', 'Crm::cases', ['filter' => 'admin']);
$routes->get('admin/crm/cases/create', 'Crm::createCase', ['filter' => 'admin']);
$routes->post('admin/crm/cases/create', 'Crm::storeCase', ['filter' => 'admin']);
$routes->get('admin/crm/cases/(:num)/edit', 'Crm::editCase/$1', ['filter' => 'admin']);
$routes->post('admin/crm/cases/(:num)/edit', 'Crm::updateCase/$1', ['filter' => 'admin']);
$routes->get('admin/crm/cases/(:num)/delete', 'Crm::deleteCase/$1', ['filter' => 'admin']);

// Public website
$routes->get('about', 'Site::show/about');
$routes->get('practice-areas', 'Site::show/practice-areas');
$routes->get('updates', 'Updates::index');
$routes->get('updates/(:segment)', 'Updates::show/$1');
$routes->get('faq', 'Site::show/faq');
$routes->get('contact', 'Site::show/contact');
$routes->get('appointment', 'Site::show/appointment');
$routes->get('bns-2023-explained', 'Site::show/bns-2023-explained');
$routes->get('divorce-separation', 'Site::show/divorce-separation');
$routes->get('grandparent-rights', 'Site::show/grandparent-rights');
$routes->get('trademark-infringement', 'Site::show/trademark-infringement');
$routes->get('domestic-violence', 'Site::show/domestic-violence');
$routes->get('criminal-cases', 'Site::show/criminal-cases');
$routes->get('economic-offence', 'Site::show/economic-offence');
$routes->get('property-tax', 'Site::show/property-tax');
$routes->get('child-custody', 'Site::show/child-custody');

// Keep catch-all LAST.
$routes->get('(:segment)', 'Site::show/$1');
