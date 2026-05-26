<?php

namespace Src\Presentation\Middleware;

use Src\Infrastructure\Auth\JwtService;
use Src\Presentation\Http\Response;

class AuthMiddleware
{
    public function handle(): bool
    {
        $headers = getallheaders();

        $authorization =
            $headers['Authorization']
            ?? $headers['authorization']
            ?? '';

        if (!$authorization) {

            Response::json([
                'message' => 'Unauthorized'
            ], 401);

            return false;
        }

        $token = str_replace(
            'Bearer ',
            '',
            $authorization
        );

        try {

            $jwt = new JwtService();

            $decoded =
                $jwt->decode($token);

            $_SERVER['user'] = (array)
                $decoded->data;

            return true;

        } catch (\Exception $e) {

            Response::json([
                'message' => 'Invalid token'
            ], 401);

            return false;
        }
    }
}