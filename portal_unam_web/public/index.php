<?php

/**
 * Front Controller - Portal de Egresados UNAM
 * Compatible con CodeIgniter 4.x
 */

// Asegura que se puede encontrar el autoloader de Composer
$autoloadFile = __DIR__ . '/../vendor/autoload.php';

if (! is_file($autoloadFile)) {
    // Intenta con la ruta relativa si está en public/
    $autoloadFile = dirname(__DIR__) . '/vendor/autoload.php';
}

if (is_file($autoloadFile)) {
    require_once $autoloadFile;
}

/*
|--------------------------------------------------------------------------
| Define las constantes base del framework
|--------------------------------------------------------------------------
*/
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

$pathsConfig = FCPATH . '../app/Config/Paths.php';
require $pathsConfig;

$paths = new Config\Paths();

/*
|--------------------------------------------------------------------------
| Bootstrap del sistema CI4
|--------------------------------------------------------------------------
*/
require $paths->systemDirectory . '/bootstrap.php';

/*
|--------------------------------------------------------------------------
| Lanzar la aplicación
|--------------------------------------------------------------------------
*/
$app = Config\Services::codeigniter();
$app->initialize();
$context = is_cli() ? 'php-cli' : 'web';
$app->setContext($context);
$app->run();
