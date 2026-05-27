<?php

namespace Modules\User\Presentation\Controllers;

use Src\Presentation\Http\Response;
use Modules\User\Infrastructure\Persistence\UserRepository;

class UserController
{
    private UserRepository $repository;

    public function __construct()
    {
        $this->repository =
            new UserRepository();
    }

    public function index(): void
    {
        $users =
            $this->repository->all();

        Response::json($users);
    }



    

    public function create(): void
{
    $data = json_decode(
        file_get_contents('php://input'),
        true
    );

    $firstName = $data['first_name'] ?? '';
    $lastName  = $data['last_name'] ?? '';
    $email     = $data['email'] ?? '';
    $password  = $data['password'] ?? '';

    if (!$firstName || !$email || !$password) {

        Response::json([
            'message' => 'Validation failed'
        ], 422);

        return;
    }

    $hashedPassword = password_hash(
        $password,
        PASSWORD_BCRYPT
    );

    $id = $this->repository->create([
        'first_name' => $firstName,
        'last_name'  => $lastName,
        'email'      => $email,
        'password'   => $hashedPassword
    ]);

    Response::json([
        'message' => 'User created',
        'id' => $id
    ]);
}

public function show(
    int $id
): void {

    $user =
        $this->repository
            ->findById($id);

    if (!$user) {

        Response::json([
            'message' => 'User not found'
        ], 404);

        return;
    }

    Response::json($user);
}

public function update(
    int $id
): void {

    $data = json_decode(
        file_get_contents('php://input'),
        true
    );

    $updated =
        $this->repository->update(
            $id,
            $data
        );

    Response::json([

        'updated' => $updated

    ]);
}

public function delete(
    int $id
): void {

    $deleted =
        $this->repository->delete($id);

    Response::json([

        'deleted' => $deleted

    ]);
}



}


