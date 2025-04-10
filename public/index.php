<?php

define('APP_ROOT', dirname(__DIR__));

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

require_once APP_ROOT . '/src/bootstrap.php';

use Slim\Factory\AppFactory;
use App\Helpers\ErrorHandler;

$app = AppFactory::create();

// Set the base path for Slim (adjust this based on your server setup)
$app->setBasePath('/php_api_v3');

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

// get all defined routes
foreach (glob(APP_ROOT . '/src/routes/*.php') as $route) {
    $routeFunction = require $route;
    if (is_callable($routeFunction)) {
        $routeFunction($app);  
    }
}

$app->get('/debug/routes', function ($request, $response) use ($app) {
    $routeCollector = $app->getRouteCollector();
    $routes = $routeCollector->getRoutes();

    $routeList = [];
    foreach ($routes as $route) {
        $methods = implode('|', $route->getMethods());
        $routeList[] = "$methods " . $route->getPattern();
    }

    $response->getBody()->write(json_encode($routeList, JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json');
});

// Add error middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
ErrorHandler::jsonErrorHandler($errorMiddleware);

// Run the app with global error handling
try {
    $app->run();
} catch (Throwable $e) {
    ErrorHandler::handleFatalError($e);
}
