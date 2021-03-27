<?php

// Base paths
define('DOCROOT', realpath(__DIR__).DIRECTORY_SEPARATOR);
define('BASE_URI', $_SERVER['BASE']);
define('WEBROOT', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].BASE_URI);


// Environment
define('ENV', $_SERVER['ENV']);

// Language, Encoding and Locales
define('LANG', 'no');
define('LOCALE', 'nb_NO');

mb_internal_encoding("UTF-8");
date_default_timezone_set('Europe/Oslo');
define('LC', setlocale(LC_ALL, 'nb_NO.utf8', 'nb_NO.utf-8', 'nb_NO', 'nor', ''));
setlocale(LC_NUMERIC, 'C');

// Directories
define('CACHE', DOCROOT.'.cache'.DIRECTORY_SEPARATOR);
define('CONTENT', DOCROOT.'..'.DIRECTORY_SEPARATOR.'sdaweb-ssl'.DIRECTORY_SEPARATOR.'__'.DIRECTORY_SEPARATOR.LANG.DIRECTORY_SEPARATOR);
