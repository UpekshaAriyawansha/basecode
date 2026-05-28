<?php

namespace Modules\User\Application\Services;

use Modules\User\Infrastructure\Persistence\UserRepository;
use Src\Infrastructure\Database\DatabaseManager;
use Modules\User\Application\Events\UserCreatedEvent;
use Src\Infrastructure\Cache\CacheManager;
use Src\Infrastructure\Pagination\Paginator;
use Modules\User\Presentation\Resources\UserResource;
use Src\Core\Exceptions\BusinessException;
use Src\Infrastructure\Events\EventDispatcher;

class UserService
{
    private UserRepository $repository;

    private DatabaseManager $db;

    private EventDispatcher $events;

    public function __construct(

        UserRepository $repository,

        DatabaseManager $db,

        EventDispatcher $events

    ) {

        $this->repository =
            $repository;

        $this->db =
            $db;

        $this->events =
            $events;
    }

    /*
    |--------------------------------------------------------------------------
    | Get Paginated Users
    |--------------------------------------------------------------------------
    */

    public function all(
        int $page = 1,
        int $perPage = 10
    ): array {

        $users =
            $this->repository
                ->paginate(
                    $page,
                    $perPage
                );

        $users =
            UserResource::collection(
                $users
            );

        return Paginator::paginate(

            $users,

            $page,

            $perPage

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Find Single User
    |--------------------------------------------------------------------------
    */

    public function find(
        int $id
    ): ?array {

        $user =
            $this->repository
                ->findById($id);

        if (!$user) {

            return null;
        }

        return UserResource::make(
            $user
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create User
    |--------------------------------------------------------------------------
    */

    public function create(
        array $data
    ): string {

        $this->db->begin();

        try {

            /*
            |--------------------------------------------------------------------------
            | Hash Password
            |--------------------------------------------------------------------------
            */

            $data['password'] =
                password_hash(
                    $data['password'],
                    PASSWORD_BCRYPT
                );

            /*
            |--------------------------------------------------------------------------
            | Create User
            |--------------------------------------------------------------------------
            */

            $id =
                $this->repository
                    ->create($data);

            /*
            |--------------------------------------------------------------------------
            | Clear Cache
            |--------------------------------------------------------------------------
            */

            CacheManager::driver()
                ->forget('users.all');

            /*
            |--------------------------------------------------------------------------
            | Dispatch Event
            |--------------------------------------------------------------------------
            */

            $this->events->dispatch(

                new UserCreatedEvent([

                    'id' => $id,

                    'email' =>
                        $data['email']

                ])

            );

            /*
            |--------------------------------------------------------------------------
            | Commit Transaction
            |--------------------------------------------------------------------------
            */

            $this->db->commit();

            return (string) $id;

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Rollback Transaction
            |--------------------------------------------------------------------------
            */

            $this->db->rollback();

            /*
            |--------------------------------------------------------------------------
            | Handle Duplicate Email
            |--------------------------------------------------------------------------
            */

            if (
                str_contains(
                    $e->getMessage(),
                    'Duplicate entry'
                )
                &&
                str_contains(
                    $e->getMessage(),
                    'email'
                )
            ) {

                throw new BusinessException(
                    'Email already exists'
                );
            }

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Update User
    |--------------------------------------------------------------------------
    */

    public function update(
        int $id,
        array $data
    ): bool {

        return $this->repository
            ->update(
                $id,
                $data
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete User
    |--------------------------------------------------------------------------
    */

    public function delete(
        int $id
    ): bool {

        return $this->repository
            ->delete($id);
    }
}