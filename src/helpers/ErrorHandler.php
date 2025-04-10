<?php

declare(strict_types=1);

namespace App\Helpers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpMethodNotAllowedException;
use Throwable;
use Slim\Psr7\Response as SlimResponse;

class ErrorHandler
{
    public static function jsonErrorHandler($errorMiddleware)
    {
        $errorMiddleware->setDefaultErrorHandler(function (
            Request $request,
            Throwable $exception,
            bool $displayErrorDetails
        ) {
            // Determine the appropriate HTTP status code
            if ($exception instanceof HttpNotFoundException) {
                $statusCode = 404;
            } elseif ($exception instanceof HttpMethodNotAllowedException) {
                $statusCode = 405;
            } else {
                $statusCode = 500;
            }

            // Construct error response
            $errorData = [
                'error' => true,
                'error_message' => $exception->getMessage(),
                'message' => 'An error occurred.',
                'code' => $statusCode,
                'trace' => $displayErrorDetails ? $exception->getTrace() : [], // Show stack trace only in debug mode
            ];

            // Create response
            $response = new SlimResponse();
            $response->getBody()->write(json_encode($errorData, JSON_PRETTY_PRINT));

            return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
        });
    }

    public static function handleFatalError(Throwable $e)
    {
        http_response_code(500);
        header('Content-Type: application/json');

        $errorData = [
            'error' => true,
            'error_message' => $e->getMessage(),
            'message' => 'An error occurred.',
            'code' => 500,
            'trace' => $e->getTrace(),
        ];

        echo json_encode($errorData, JSON_PRETTY_PRINT);
        exit;
    }
}
