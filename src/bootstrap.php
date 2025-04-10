<?php

require_once APP_ROOT . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Core\Database; 

$dotenv = Dotenv::createImmutable(APP_ROOT);
$dotenv->load();

$config = require_once APP_ROOT . '/src/config/config.php';

// Initialize database
Database::init(
    $_ENV['DB_HOST'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD'],
    $_ENV['DB_NAME'],
    (int)$_ENV['DB_PORT']
);

if ($_ENV['APP_ENV'] === 'development') {
    ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

return $config;