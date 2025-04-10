<?php
declare(strict_types=1);

use Slim\App;
use App\Controllers\PaymentController;

return function (App $app) {
    $app->get('/payment-status', [PaymentController::class, 'checkPaymentStatus']);
};
