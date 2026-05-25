<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    /*
    |--------------------------------------------------------------------------
    | Base URL — se sobreescribe desde .env:
    |   app.baseURL = 'https://XXXXX.ngrok-free.app/'
    |--------------------------------------------------------------------------
    */
    public string $baseURL = 'http://localhost/portal_unam_web/public/';

    /*
    | Hosts permitidos — ngrok se agrega automáticamente via .env
    */
    public array $allowedHostnames = [];

    public string $indexPage = '';

    public string $uriProtocol = 'REQUEST_URI';

    public bool $forceGlobalSecureRequests = false;

    public string $proxyIPs = '';

    /*
    |--------------------------------------------------------------------------
    | Session
    |--------------------------------------------------------------------------
    */
    public string $sessionDriver            = \CodeIgniter\Session\Handlers\FileSessionHandler::class;
    public string $sessionCookieName        = 'ci_session';
    public int    $sessionExpiration        = 7200;
    public string $sessionSavePath          = WRITEPATH . 'session';
    public bool   $sessionMatchIP           = false;
    public int    $sessionTimeToUpdate      = 300;
    public bool   $sessionRegenerateDestroy = false;

    /*
    |--------------------------------------------------------------------------
    | Cookie
    |--------------------------------------------------------------------------
    */
    public string $cookiePrefix   = '';
    public string $cookieDomain   = '';
    public string $cookiePath     = '/';
    public bool   $cookieSecure   = false;
    public bool   $cookieHTTPOnly = false;
    public string $cookieSameSite = 'Lax';

    /*
    |--------------------------------------------------------------------------
    | CSRF
    |--------------------------------------------------------------------------
    */
    public string $CSRFTokenName   = 'csrf_token';
    public string $CSRFHeaderName  = 'X-CSRF-TOKEN';
    public string $CSRFCookieName  = 'csrf_cookie';
    public int    $CSRFExpire      = 7200;
    public bool   $CSRFRegenerate  = true;
    public array  $CSRFExcludeURIs = [
        'api/*',
        'empleadores/registrar',
        'ofertas/registrar',
    ];
    public string $CSRFSameSite    = 'Lax';

    /*
    |--------------------------------------------------------------------------
    | Locale
    |--------------------------------------------------------------------------
    */
    public string $defaultLocale    = 'es';
    public string $negotiateLocale  = 'false';
    public array  $supportedLocales = ['es'];
    public string $appTimezone      = 'America/Lima';
    public string $charset          = 'UTF-8';
}
