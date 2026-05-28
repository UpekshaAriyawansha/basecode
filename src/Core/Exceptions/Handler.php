<?php

namespace Src\Core\Exceptions;

use Throwable;
use Src\Validation\ValidationException;
use Src\Core\Exceptions\BusinessException;
use Src\Infrastructure\Logging\Logger;

class Handler
{
    public static function register(): void
    {
        set_exception_handler(

            [self::class, 'handleException']

        );

        set_error_handler(

            [self::class, 'handleError']

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Handle Exceptions
    |--------------------------------------------------------------------------
    */

    public static function handleException(
        Throwable $exception
    ): void {

        header(
            'Content-Type: application/json'
        );

        /*
        |--------------------------------------------------------------------------
        | Validation Exception
        |--------------------------------------------------------------------------
        */

        if (
            $exception instanceof
            ValidationException
        ) {

            http_response_code(422);

            echo json_encode([

                'success' => false,

                'message' =>
                    'Validation failed',

                'errors' =>
                    $exception->errors()

            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Business Exception
        |--------------------------------------------------------------------------
        */

        if (
            $exception instanceof
            BusinessException
        ) {

            http_response_code(400);

            echo json_encode([

                'success' => false,

                'message' =>
                    $exception->getMessage()

            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Generic Exceptions
        |--------------------------------------------------------------------------
        */

        http_response_code(500);

        $debug =
            config('app.debug');

        $response = [

            'success' => false,

            'message' =>
                'Internal Server Error'

        ];

        if ($debug) {

            $response['exception'] =
                $exception->getMessage();

            $response['file'] =
                $exception->getFile();

            $response['line'] =
                $exception->getLine();
        }

        echo json_encode($response);

        /*
        |--------------------------------------------------------------------------
        | Log Exception
        |--------------------------------------------------------------------------
        */

        Logger::channel()->error(

            $exception->getMessage(),

            [

                'file' =>

                    $exception->getFile(),

                'line' =>

                    $exception->getLine(),

                'trace' =>

                    $exception->getTraceAsString()

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Convert PHP Errors To Exceptions
    |--------------------------------------------------------------------------
    */

    public static function handleError(
        int $severity,
        string $message,
        string $file,
        int $line
    ): void {

        throw new \ErrorException(

            $message,

            0,

            $severity,

            $file,

            $line

        );
    }
}