<?php

use Src\Support\Config;


// if (!function_exists('env')) {

//     function env(
//         string $key,
//         mixed $default = null
//     ): mixed {

//         return $_ENV[$key] ?? $default;
//     }
// }


if (!function_exists('config')) {

    function config(
        string $key,
        mixed $default = null
    ): mixed {

        return Config::get(
            $key,
            $default
        );
    }
}
