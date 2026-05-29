<?php

namespace Modules\User\Presentation\Controllers;

use Src\Presentation\Controllers\Controller;
// use Src\Presentation\Validation\Validator;
use Modules\User\Infrastructure\Persistence\UserRepository;
use Modules\User\Application\Services\UserService;
use Src\Presentation\Http\Response;
use Src\Validation\Validator;
use Modules\User\Presentation\Requests\StoreUserRequest;

class UserController extends Controller
{
    // private UserRepository $repository;

    private UserService $service;

    public function __construct(
        UserService $service
            ) {

                $this->service =
                    $service;
            }

public function index(): void
    {
        $page =
            (int) (
                $_GET['page']
                ?? 1
            );

        $perPage =
            (int) (
                $_GET['per_page']
                ?? 10
            );

        $users =
            $this->service
                ->all(
                    $page,
                    $perPage
                );

        Response::json($users);
    }


    public function show(
        int $id
    ): void {

       $user =
            $this->service
                ->find($id);

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
        $request =
            new StoreUserRequest();

        $data =
            $request->validate();

        $id =
            $this->service
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
            $this->service
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
            $this->service
                ->delete($id);

        $this->success(
            'User deleted',
            [
                'deleted' => $deleted
            ]
        );
    }
}