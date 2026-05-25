<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Dashboard
$routes->get('/',          'DashboardController::index');
$routes->get('/dashboard', 'DashboardController::index');

// Egresados
$routes->get('/egresados',          'EgresadosController::index');
$routes->get('/egresados/buscar',   'EgresadosController::buscar');
$routes->get('/egresados/(:num)',   'EgresadosController::detalle/$1');

// Empleadores
$routes->get('/empleadores',            'EmpleadoresController::index');
$routes->get('/empleadores/registrar',  'EmpleadoresController::registrar');
$routes->post('/empleadores/registrar', 'EmpleadoresController::guardar');
$routes->get('/empleadores/(:num)',     'EmpleadoresController::detalle/$1');

// Ofertas laborales
$routes->get('/ofertas',            'OfertasController::index');
$routes->get('/ofertas/registrar',  'OfertasController::registrar');
$routes->post('/ofertas/registrar', 'OfertasController::guardar');
$routes->get('/ofertas/(:num)',     'OfertasController::detalle/$1');

// KPIs API
$routes->get('/api/kpis/resumen',           'DashboardController::kpiResumen');
$routes->get('/api/kpis/por-escuela',       'DashboardController::kpiPorEscuela');
$routes->get('/api/kpis/por-anio',          'DashboardController::kpiPorAnio');
$routes->get('/api/kpis/por-sede',          'DashboardController::kpiPorSede');
$routes->get('/api/kpis/por-sexo',          'DashboardController::kpiPorSexo');
$routes->get('/api/kpis/titulados-escuela', 'DashboardController::kpiTituladosPorEscuela');

// API empleadores y auth (del public/index.php original)
$routes->get('/api/empleadores',       'EmpleadoresController::apiListar');
$routes->post('/api/auth/register',    'AuthController::register');
$routes->post('/api/auth/login',       'AuthController::login');
$routes->post('/api/auth/logout',      'AuthController::logout');
$routes->post('/api/seed_demo',        'DashboardController::seedDemo');
$routes->get('/api/ofertas',           'OfertasController::apiListar');
$routes->post('/api/ofertas',          'OfertasController::apiGuardar');
