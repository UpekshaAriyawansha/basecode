<?php

namespace Src\Core\Exceptions;

use Throwable;

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

    public static function handleException(
        Throwable $exception
    ): void {

        http_response_code(500);

        header(
            'Content-Type: application/json'
        );

    $debug =
        $_ENV['APP_DEBUG']
        ?? false;

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

            self::log($exception);
        }

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

    private static function log(
        Throwable $exception
    ): void {

        $logDir =
            __DIR__ . '/../../../storage/logs';

        if (!is_dir($logDir)) {

            mkdir(
                $logDir,
                0777,
                true
            );
        }

        $logFile =
            $logDir . '/app.log';

        $message = sprintf(

            "[%s] %s in %s:%d\n",

            date('Y-m-d H:i:s'),

            $exception->getMessage(),

            $exception->getFile(),

            $exception->getLine()

        );

        file_put_contents(

            $logFile,

            $message,

            FILE_APPEND

        );
    }
}