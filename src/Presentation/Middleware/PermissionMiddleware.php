<?php

namespace Src\Presentation\Middleware;

use Src\Presentation\Http\Response;
use Modules\User\Infrastructure\Persistence\UserRepository;

class PermissionMiddleware
{
    private array $permissions;

    public function __construct(
        array $permissions = []
    ) {

        $this->permissions =
            $permissions;
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

        $repository =
            new UserRepository();

        $userPermissions =
            $repository->permissions(
                $user['id']
            );

        foreach (
            $this->permissions
            as $permission
        ) {

            if (
                !in_array(
                    $permission,
                    $userPermissions
                )
            ) {

                Response::json([
                    'message' => 'Forbidden'
                ], 403);

                return false;
            }
        }

        return true;
    }
}