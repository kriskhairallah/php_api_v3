<?php
declare(strict_types=1);

use Slim\App;
use App\Controllers\ClientController;

return function (App $app) {
    $app->post('/clients', [ClientController::class, 'createClient']);
    $app->post('/client-login', [ClientController::class, 'clientLogin']);
};
