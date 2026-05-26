<?php

namespace Modules\User\Presentation\Controllers;

use Modules\User\Infrastructure\Persistence\UserRepository;
use Src\Infrastructure\Auth\JwtService;
use Src\Presentation\Http\Response;

class AuthController
{
    public function login(): void
    {
        $email =
            $_POST['email'] ?? '';

        $password =
            $_POST['password'] ?? '';

        $repository =
            new UserRepository();

        $user =
            $repository->findByEmail($email);

        if (!$user) {

            Response::json([
                'message' => 'Invalid credentials'
            ]);

            return;
        }

        if (!password_verify(
            $password,
            $user['password']
        )) {

            Response::json([
                'message' => 'Invalid credentials'
            ]);

            return;
        }

        $jwt = new JwtService();

        $token = $jwt->generate([

            'id' => $user['id'],

            'email' => $user['email']

        ]);

        Response::json([

            'token' => $token,

            'user' => [

                'id' => $user['id'],

                'email' => $user['email']

            ]

        ]);
    }
}

