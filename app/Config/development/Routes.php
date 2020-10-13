<?php
namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */
$routes->get('/loaddb', 'LoadDB::index');
