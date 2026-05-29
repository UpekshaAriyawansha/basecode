<?php

namespace Src\Providers;

use Src\Core\Providers\ServiceProvider;

use Src\Infrastructure\Database\DatabaseManager;
use Src\Infrastructure\Events\EventDispatcher;

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

        $this->app->singleton(

            DatabaseManager::class,

            fn () => new DatabaseManager()

        );

        /*
        |--------------------------------------------------------------------------
        | Event Dispatcher
        |--------------------------------------------------------------------------
        */

        $this->app->singleton(

            EventDispatcher::class,

            fn () => new EventDispatcher()

        );
    }

    public function boot(): void
    {
        //
    }
}