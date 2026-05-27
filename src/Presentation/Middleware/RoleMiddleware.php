<?php

namespace Src\Presentation\Middleware;

use Src\Presentation\Http\Response;

class RoleMiddleware
{
    private array $roles;

    public function __construct(
        array $roles = []
    ) {

        $this->roles = $roles;
    }

    public function handle(): bool
    {
        $user =
            $_SERVER['user']
            ?? null;

        if (!$user) {

            Response::json([
                'message' => 'Unauthorized'
            ], 401);

            return false;
        }

        if (
            !in_array(
                $user['role'],
                $this->roles
            )
        ) {

            Response::json([
                'message' => 'Forbidden'
            ], 403);

            return false;
        }

        return true;
    }
}