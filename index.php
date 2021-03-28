<?php

// Include autoloader

require 'vendor/autoload.php';

// Error handling
$errorHandler = new Bibelstudiet\Error\ErrorHandler();
error_reporting(E_ALL);
set_error_handler([$errorHandler, 'error_handler']);
set_exception_handler([$errorHandler, 'exception_handler']);

// Remove default headers like X-Powered-By
header_remove();

// Base paths
define('DOCROOT', realpath(__DIR__).DIRECTORY_SEPARATOR);
define('WEBROOT', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['BASE']);

// Directories
define('CACHE', DOCROOT.'.cache'.DIRECTORY_SEPARATOR);
define('CONTENT', DOCROOT.'..'.DIRECTORY_SEPARATOR.'sdaweb-ssl'.DIRECTORY_SEPARATOR.'__'.DIRECTORY_SEPARATOR.'no'.DIRECTORY_SEPARATOR);

// Environment
define('ENV', $_SERVER['ENV']);

// Localization
mb_internal_encoding("UTF-8");
date_default_timezone_set('Europe/Oslo');
setlocale(LC_ALL, 'nb_NO.utf8', 'nb_NO.utf-8', 'nb_NO', 'nor', '');

// Handle request
$method = $_SERVER['REQUEST_METHOD'];
$path = substr($_SERVER['REQUEST_URI'], strlen($_SERVER['BASE']) - 1);

$api = new Bibelstudiet\Api\Api(require('./routes.php'));
$api->serve($method, $path);
