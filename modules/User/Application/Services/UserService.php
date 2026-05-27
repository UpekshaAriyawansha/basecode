<?php

namespace Modules\User\Application\Services;

use Modules\User\Infrastructure\Persistence\UserRepository;

use Src\Infrastructure\Database\DatabaseManager;

use Modules\User\Application\Events\UserCreatedEvent;

use Src\Infrastructure\Cache\CacheManager;


class UserService
{
    private UserRepository $repository;

    private DatabaseManager $db;

    public function __construct(
    UserRepository $repository,
    DatabaseManager $db
        ) {

            $this->repository =
                $repository;

            $this->db =
                $db;
        }

public function all(): array
    {
        $cache =
            CacheManager::driver();

        $users =
            $cache->get('users.all');

        if ($users) {

            return $users;
        }

        $users =
            $this->repository->all();

        $cache->put(
            'users.all',
            $users,
            60
        );

        return $users;
    }


    public function find(
        int $id
    ): ?array {

        return $this->repository
            ->findById($id);
    }




public function create(
    array $data
): string {

    $this->db->begin();

    try {

        $data['password'] =
            password_hash(
                $data['password'],
                PASSWORD_BCRYPT
            );

        $id =
            $this->repository
                ->create($data);

        CacheManager::driver()
            ->forget('users.all');

        $events =
            $GLOBALS['events'];

        $events->dispatch(

            new UserCreatedEvent([

                'id' => $id,

                'email' =>
                    $data['email']

            ])

        );

        $this->db->commit();

        return $id;

    } catch (\Throwable $e) {

        $this->db->rollback();

        throw $e;
    }
}



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

    public function delete(
        int $id
    ): bool {

        return $this->repository
            ->delete($id);
    }
}