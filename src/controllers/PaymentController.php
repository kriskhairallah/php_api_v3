<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Core\Database as DB;

class PaymentController {

    public function checkPaymentStatus(Request $request, Response $response) {
        $authHeader = $request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $authHeader);
        $decoded = validateJWT($token);

        if (!$decoded) {
            return $response->withJson(['error' => 'Unauthorized'], 401);
        }

        $payment = DB::queryFirstRow("SELECT status FROM payments WHERE user_id = %d ORDER BY payment_date DESC", $decoded->sub);
        
        if (!$payment || $payment['status'] !== 'completed') {
            return $response->withJson(['error' => 'Payment required'], 403);
        }

        return $response->withJson(['message' => 'Payment verified']);
    }
}
