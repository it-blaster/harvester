<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#checking-symfony-application-configuration-and-setup
// for more information
//umask(0000);

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
if (/* isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    ||*/ (!in_array(@$_SERVER['REMOTE_ADDR'], array(
        '127.0.0.1',
        'fe80::1',
        '::1',
        '92.242.13.250',
        '217.114.228.99',
        '46.235.190.9',
        '188.243.104.69',
        '192.168.6.5',
        '5.189.85.187', //Serg Yakimov
        '94.137.242.177', //Gordeeva
        '92.242.12.62'))
    && !(preg_match('~^10\.~', @$_SERVER['REMOTE_ADDR']) || preg_match('~192\.168\.(74|0|100|5|4)\.\d+~', @$_SERVER['REMOTE_ADDR'])))
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

/**
 * @var Composer\Autoload\ClassLoader $loader
 */
$loader = require __DIR__.'/../app/autoload.php';

Debug::enable();

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
