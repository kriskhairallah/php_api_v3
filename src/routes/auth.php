<?php

namespace App\Routes;

use App\Controllers\AuthController;
use Slim\App;

return function (App $app) {
    $app->post('/register', [AuthController::class, 'register']);
    $app->post('/login', [AuthController::class, 'login']);
};