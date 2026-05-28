<?php

namespace Src\Providers;

use Src\Infrastructure\Database\DatabaseManager;

class AppServiceProvider
    extends ServiceProvider
{
    public function register():
        void {

        /*
        |--------------------------------------------------------------------------
        | Database Manager
        |--------------------------------------------------------------------------
        */

        $this->container->bind(

            DatabaseManager::class,

            fn () => new DatabaseManager()

        );
    }
}