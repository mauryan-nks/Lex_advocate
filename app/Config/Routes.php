<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Site::index');
$routes->get('home', 'Site::index');
$routes->get('about', 'Site::show/about');
$routes->get('practice-areas', 'Site::show/practice-areas');
$routes->get('updates', 'Site::show/updates');
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
$routes->get('(:segment)', 'Site::show/$1');
