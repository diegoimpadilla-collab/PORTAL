<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Dashboard principal
$routes->get('/', 'DashboardController::index');
$routes->get('/dashboard', 'DashboardController::index');

// Egresados
$routes->get('/egresados',            'EgresadosController::index');
$routes->get('/egresados/buscar',     'EgresadosController::buscar');
$routes->get('/egresados/(:num)',     'EgresadosController::detalle/$1');

// Empleadores
$routes->get('/empleadores',          'EmpleadoresController::index');
$routes->get('/empleadores/(:num)',   'EmpleadoresController::detalle/$1');

// Ofertas laborales
$routes->get('/ofertas',              'OfertasController::index');
$routes->get('/ofertas/(:num)',       'OfertasController::detalle/$1');

// KPIs API (para gráficos AJAX)
$routes->get('/api/kpis/resumen',          'DashboardController::kpiResumen');
$routes->get('/api/kpis/por-escuela',      'DashboardController::kpiPorEscuela');
$routes->get('/api/kpis/por-anio',         'DashboardController::kpiPorAnio');
$routes->get('/api/kpis/por-sede',         'DashboardController::kpiPorSede');
$routes->get('/api/kpis/por-sexo',         'DashboardController::kpiPorSexo');
$routes->get('/api/kpis/titulados-escuela','DashboardController::kpiTituladosPorEscuela');
