<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Site::index');
$routes->get('home', 'Site::index');
$routes->get('about', 'Site::show/about');
$routes->get('practice-areas', 'Site::show/practice-areas');
$routes->get('updates', 'Updates::index');
$routes->get('updates/(:segment)', 'Updates::show/$1');
$routes->get('faq', 'Site::show/faq');
$routes->get('contact', 'Site::show/contact');
$routes->get('appointment', 'Site::show/appointment');
$routes->post('contact/submit', 'Enquiries::contactStore');
$routes->post('appointment/submit', 'Enquiries::appointmentStore');
$routes->get('bns-2023-explained', 'Site::show/bns-2023-explained');
$routes->get('divorce-separation', 'Site::show/divorce-separation');
$routes->get('grandparent-rights', 'Site::show/grandparent-rights');
$routes->get('trademark-infringement', 'Site::show/trademark-infringement');
$routes->get('domestic-violence', 'Site::show/domestic-violence');
$routes->get('criminal-cases', 'Site::show/criminal-cases');
$routes->get('economic-offence', 'Site::show/economic-offence');
$routes->get('property-tax', 'Site::show/property-tax');
$routes->get('child-custody', 'Site::show/child-custody');
// Admin CRM
$routes->get('login','Auth::login'); $routes->post('login','Auth::authenticate'); $routes->get('logout','Auth::logout');
$routes->get('dashboard','Dashboard::index',['filter'=>'auth']); $routes->get('admin','Admin::index',['filter'=>'admin']);
$routes->group('admin/cms',['filter'=>'admin'],static function($routes){
$routes->get('/','Cms::index');
$routes->get('updates','Cms::updates'); $routes->get('updates/create','Cms::updateCreate'); $routes->post('updates/store','Cms::updateStore'); $routes->get('updates/edit/(:num)','Cms::updateEdit/$1'); $routes->post('updates/save/(:num)','Cms::updateSave/$1'); $routes->get('updates/delete/(:num)','Cms::updateDelete/$1');
$routes->get('practice-areas','Cms::practices'); $routes->get('practice-areas/create','Cms::practiceCreate'); $routes->post('practice-areas/store','Cms::practiceStore'); $routes->get('practice-areas/edit/(:num)','Cms::practiceEdit/$1'); $routes->post('practice-areas/save/(:num)','Cms::practiceSave/$1'); $routes->get('practice-areas/delete/(:num)','Cms::practiceDelete/$1');
$routes->get('practice-areas/builder/(:num)','Cms::practiceBuilder/$1'); $routes->post('templates/store','Cms::templateStore'); $routes->post('templates/delete/(:num)','Cms::templateDelete/$1');
});
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
$routes->get('admin/enquiries', 'EnquiriesAdmin::index', ['filter' => 'admin']);
$routes->get('admin/enquiries/contact', 'EnquiriesAdmin::contact', ['filter' => 'admin']);
$routes->get('admin/enquiries/appointments', 'EnquiriesAdmin::appointments', ['filter' => 'admin']);
$routes->get('admin/enquiries/(:num)', 'EnquiriesAdmin::show/$1', ['filter' => 'admin']);
$routes->post('admin/enquiries/(:num)/status', 'EnquiriesAdmin::status/$1', ['filter' => 'admin']);
$routes->get('admin/enquiries/(:num)/delete', 'EnquiriesAdmin::delete/$1', ['filter' => 'admin']);


$routes->get('(:segment)', 'Site::show/$1');
