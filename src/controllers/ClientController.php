<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Core\Database as DB;

class ClientController {

    public function createClient(Request $request, Response $response) {
        $authHeader = $request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $authHeader);
        $decoded = validateJWT($token);

        if (!$decoded || $decoded->role !== 'company_user') {
            return $response->withJson(['error' => 'Unauthorized'], 401);
        }

        $data = $request->getParsedBody();
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        DB::insert('clients', [
            'user_id' => $decoded->sub,
            'email' => $data['email'],
            'password' => $hashedPassword
        ]);

        return $response->withJson(['message' => 'Client created successfully']);
    }

    public function clientLogin(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $client = DB::queryFirstRow("SELECT * FROM clients WHERE email = %s", $data['email']);

        if (!$client || !password_verify($data['password'], $client['password'])) {
            return $response->withJson(['error' => 'Invalid credentials'], 401);
        }

        $token = generateJWT($client['id'], 'client');
        return $response->withJson(['token' => $token]);
    }
}
