<?php

namespace Src\Presentation\Http;

class Response
{
    public static function json(
        array $data
    ): void {

        header('Content-Type: application/json');

        echo json_encode(
            $data,
            JSON_PRETTY_PRINT
        );
    }
}