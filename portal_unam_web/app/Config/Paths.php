<?php

namespace Config;

class Paths
{
    /**
     * Ruta al directorio 'app' de la aplicación.
     */
    public string $appDirectory = __DIR__ . '/..';

    /**
     * Ruta al directorio 'system' de CodeIgniter.
     * Cuando instalas via Composer queda en vendor/codeigniter4/framework/system
     */
    public string $systemDirectory = __DIR__ . '/../../vendor/codeigniter4/framework/system';

    /**
     * Ruta al directorio 'writable'.
     */
    public string $writableDirectory = __DIR__ . '/../../writable';

    /**
     * Ruta a los tests.
     */
    public string $testsDirectory = __DIR__ . '/../../tests';

    /**
     * Nombre del archivo de rutas de la vista.
     */
    public array $viewDirectory = [];
}
