<?php

namespace Src\Presentation\Controllers;

use Src\Presentation\Http\Response;

class Controller
{
    protected function json(
        array $data,
        int $status = 200
    ): void {

        Response::json(
            $data,
            $status
        );
    }

    protected function success(
        string $message,
        array $data = []
    ): void {

        $this->json([

            'success' => true,

            'message' => $message,

            'data' => $data

        ]);
    }

    protected function error(
        string $message,
        int $status = 400
    ): void {

        $this->json([

            'success' => false,

            'message' => $message

        ], $status);
    }
}