<?php

// Include autoloader and stuff
require 'vendor/autoload.php';
require 'constants.php';

// Error handling
error_reporting(E_ALL);
set_error_handler('ErrorHandler::error_handler');
set_exception_handler('ErrorHandler::exception_handler');

// Get path from htaccess parameter
$_SERVER['PATH_INFO'] = isset($_GET['path_uri']) ? $_GET['path_uri'] : '/';
unset($_GET['path_uri']);

// Remove default headers like X-Powered-By
header_remove();

// Kick things off
Api::init()->serve();
