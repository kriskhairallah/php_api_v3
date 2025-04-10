<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;
use App\Core\Database as DB;

class AuthController {
    
    public function register(Request $request, Response $response) {
        $data = $request->getParsedBody();

        // Validate input
        if (!v::email()->validate($data['email']) || !v::length(6, null)->validate($data['password'])) {
            return $response->withJson(['error' => 'Invalid input'], 400);
        }

        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        // Insert user into DB
        try {
            DB::insert('users', [
                'company_name' => $data['company_name'] ?? '',
                'email' => $data['email'],
                'password' => $hashedPassword
            ]);
        } catch (\Exception $e) {
            return $response->withJson([
            		'message' => 'User already exists', 
								'error' => $e->getMessage()
            	],400);
        }

        return $response->withJson(['message' => 'User registered successfully']);
    }

    public function login(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $user = DB::queryFirstRow("SELECT * FROM users WHERE email = %s", $data['email']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            return $response->withJson(['error' => 'Invalid credentials'], 401);
        }

        if (!$user['is_active']) {
            return $response->withJson(['error' => 'Payment required'], 403);
        }

        $token = generateJWT($user['id'], 'company_user');
        return $response->withJson(['token' => $token]);
    }
}
