<?php
declare(strict_types=1);

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function generateJWT(int $user_id, string $role): string {
    $payload = [
        'sub' => $user_id,
        'role' => $role,
        'exp' => time() + 3600, // Token valid for 1 hour
    ];
    return JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
}

function validateJWT(string $token) {
    try {
        return JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
    } catch (Exception $e) {
        return null;
    }
}
