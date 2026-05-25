<?php

/**
 * CodeIgniter 4 - Front Controller
 * Portal de Egresados UNAM Moquegua
 *
 * Compatible con CodeIgniter 4.5+
 */

// Ruta al autoloader de Composer
$autoloadFile = __DIR__ . '/../vendor/autoload.php';
if (is_file($autoloadFile)) {
    require_once $autoloadFile;
}

// Definir la ruta base del front controller
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Cargar Paths
$pathsConfig = FCPATH . '../app/Config/Paths.php';
require_once $pathsConfig;
$paths = new Config\Paths();

// Cargar bootstrap de CI4
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
require_once $bootstrap;

// Lanzar la app
$app = Config\Services::codeigniter();
$app->initialize();
$context = is_cli() ? 'php-cli' : 'web';
$app->setContext($context);
$app->run();
