<?php

namespace Modules\User\Presentation\Resources;

class UserResource
{
    public static function make(
        array $user
    ): array {

        return [

            'id' =>
                $user['id'],

            'name' =>

                trim(

                    ($user['first_name'] ?? '') .

                    ' ' .

                    ($user['last_name'] ?? '')

                ),

            'email' =>
                $user['email'],

            'status' =>
                $user['status']
                ?? null,

            'created_at' =>
                $user['created_at']
                ?? null

        ];
    }

    public static function collection(
        array $users
    ): array {

        return array_map(

            fn ($user) =>

                self::make($user),

            $users

        );
    }
}