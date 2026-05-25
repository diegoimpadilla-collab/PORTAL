<?php

namespace Config;

class Paths
{
    /**
     * Path to the application directory.
     */
    public string $appDirectory = FCPATH . '../app';

    /**
     * Path to the system directory.
     * CI4.5+ via Composer: vendor/codeigniter4/framework/system
     */
    public string $systemDirectory = FCPATH . '../vendor/codeigniter4/framework/system';

    /**
     * Path to the writable directory.
     */
    public string $writableDirectory = FCPATH . '../writable';

    /**
     * Path to the tests directory.
     */
    public string $testsDirectory = FCPATH . '../tests';
}
