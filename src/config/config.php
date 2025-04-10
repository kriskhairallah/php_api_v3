<?php

declare(strict_types=1);

/**
 * Application Configuration
 * 
 * This file contains all application configuration settings.
 * For security, sensitive values should be loaded from environment variables.
 */

return [
    'database' => [
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'username' => $_ENV['DB_USERNAME'] ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? 'root',
        'name' => $_ENV['DB_NAME'] ?? 'php_api_v2',
        'port' => $_ENV['DB_PORT'] ? (int)$_ENV['DB_PORT'] : 3306,
    ],
    'api' => [
        'base_url' => $_ENV['APP_URL'] ?? 'http://localhost:8888/php_api_v2',
    ],
    'environment' => [
        'mode' => $_ENV['APP_ENV'] ?? 'development',
    ],
    'jwt' => [
        'secret' => $_ENV['JWT_SECRET'] ?? 'your-secret-key-here',
        'expiration' => 24 * 60 * 60, // 24 hours
    ],
    'mail' => [
        'host' => $_ENV['SMTP_HOST'] ?? 'smtp.mailtrap.io',
        'port' => $_ENV['SMTP_PORT'] ? (int)$_ENV['SMTP_PORT'] : 2525,
        'username' => $_ENV['SMTP_USERNAME'] ?? '',
        'password' => $_ENV['SMTP_PASSWORD'] ?? '',
        'from_address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@example.com',
        'from_name' => $_ENV['MAIL_FROM_NAME'] ?? 'PHP API Service',
    ],
    'cors' => [
        'allowed_origins' => [$_ENV['CORS_ORIGIN'] ?? 'http://localhost:3000'],
    ]
];