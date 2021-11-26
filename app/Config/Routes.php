<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Inicio');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
//$routes->get('/', 'Home::index');
$routes->get('/', 'Inicio::index');
$routes->post('/pruebaBD', 'Inicio::pruebaConexion');
$routes->post('/pruebaTR', 'Inicio::pruebaTR');
$routes->post('/pruebaGraficos', 'Inicio::pruebaGraficos');
$routes->post('/pruebaAnalitico', 'Inicio::pruebaAnalitico');
$routes->post('/inicioSesion', 'Inicio::inicioSesion');
$routes->post('/estacion', 'Inicio::estacion');
$routes->post('/graficas', 'Inicio::graficas');
$routes->post('/alarmas', 'Inicio::alarmas');
$routes->post('/informes', 'Inicio::informes');
$routes->post('/comunicaciones', 'Inicio::comunicaciones');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
