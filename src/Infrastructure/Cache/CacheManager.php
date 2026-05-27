<?php

namespace Src\Infrastructure\Cache;

class CacheManager
{
    public static function driver():
        CacheInterface
    {

        $driver =
            $_ENV['CACHE_DRIVER']
            ?? 'file';

        return match ($driver) {

            'file' =>
                new FileCache(),

            default =>
                new FileCache()
        };
    }
}