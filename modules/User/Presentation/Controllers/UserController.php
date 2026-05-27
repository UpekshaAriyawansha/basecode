<?php

namespace Modules\User\Presentation\Controllers;

use Src\Presentation\Controllers\Controller;

use Src\Presentation\Validation\Validator;

use Modules\User\Infrastructure\Persistence\UserRepository;

class UserController extends Controller
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

        $this->json($users);
    }

    public function show(
        int $id
    ): void {

        $user =
            $this->repository
                ->findById($id);

        if (!$user) {

            $this->error(
                'User not found',
                404
            );

            return;
        }

        $this->json($user);
    }

    public function create(): void
    {
        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        $validator =
            new Validator();

        $valid =
            $validator->validate(

                $data,

                [

                    'first_name' => 'required',

                    'email' =>
                        'required|email',

                    'password' =>
                        'required'

                ]

            );

        if (!$valid) {

            $this->json([

                'errors' =>
                    $validator->errors()

            ], 422);

            return;
        }

        $data['password'] =
            password_hash(
                $data['password'],
                PASSWORD_BCRYPT
            );

        $id =
            $this->repository
                ->create($data);

        $this->success(
            'User created',
            [
                'id' => $id
            ]
        );
    }

    public function update(
        int $id
    ): void {

        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        $updated =
            $this->repository
                ->update(
                    $id,
                    $data
                );

        $this->success(
            'User updated',
            [
                'updated' => $updated
            ]
        );
    }

    public function delete(
        int $id
    ): void {

        $deleted =
            $this->repository
                ->delete($id);

        $this->success(
            'User deleted',
            [
                'deleted' => $deleted
            ]
        );
    }
}