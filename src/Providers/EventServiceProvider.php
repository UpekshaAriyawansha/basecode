<?php

namespace Src\Providers;

use Src\Infrastructure\Events\EventDispatcher;

class EventServiceProvider
    extends ServiceProvider
{
    public function register():
        void {

        $events =
            new EventDispatcher();

        /*
        |--------------------------------------------------------------------------
        | Register In Container
        |--------------------------------------------------------------------------
        */

        $this->container->bind(

            EventDispatcher::class,

            fn () => $events

        );
    }
}