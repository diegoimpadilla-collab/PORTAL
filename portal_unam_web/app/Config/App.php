<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    /**
     * Base URL — se sobreescribe con .env:
     *   app.baseURL = 'https://xxxxx.ngrok-free.app/'
     */
    public string $baseURL = 'http://localhost:8080/';

    /**
     * Allowed Hostnames — agrega aquí tu subdominio ngrok
     * para evitar el error "The action you requested is not allowed."
     */
    public array $allowedHostnames = [];

    public string $indexPage = '';

    public string $uriProtocol = 'REQUEST_URI';

    public string $defaultLocale = 'es';

    public string $negotiateLocale = 'false';

    public array $supportedLocales = ['es'];

    public string $appTimezone = 'America/Lima';

    public string $charset = 'UTF-8';

    public bool $forceGlobalSecureRequests = false;

    public string $proxyIPs = '';

    public string $CSRFTokenName    = 'csrf_token';
    public string $CSRFHeaderName   = 'X-CSRF-TOKEN';
    public string $CSRFCookieName   = 'csrf_cookie';
    public int    $CSRFExpire       = 7200;
    public bool   $CSRFRegenerate   = true;
    public array  $CSRFExcludeURIs  = [];
    public string $CSRFSameSite     = 'Lax';

    public string $cookiePrefix    = '';
    public string $cookieDomain    = '';
    public string $cookiePath      = '/';
    public bool   $cookieSecure    = false;
    public bool   $cookieHTTPOnly  = false;
    public string $cookieSameSite  = 'Lax';

    public string $sessionDriver            = 'CodeIgniter\Session\Handlers\FileSessionHandler';
    public string $sessionCookieName        = 'ci_session';
    public int    $sessionExpiration        = 7200;
    public string $sessionSavePath          = WRITEPATH . 'session';
    public bool   $sessionMatchIP           = false;
    public int    $sessionTimeToUpdate      = 300;
    public bool   $sessionRegenerateDestroy = false;

    public string $encryptionKey  = '';

    public bool   $reverseProxy   = false;
}
