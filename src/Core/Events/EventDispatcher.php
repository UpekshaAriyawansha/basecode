<?php

namespace Src\Core\Events;

class EventDispatcher
{
    private array $listeners = [];

    public function listen(
        string $event,
        callable $listener
    ): void {

        $this->listeners[$event][] =
            $listener;
    }

    public function dispatch(
        object $event
    ): void {

        $eventName =
            get_class($event);

        if (
            !isset(
                $this->listeners[$eventName]
            )
        ) {

            return;
        }

        foreach (
            $this->listeners[$eventName]
            as $listener
        ) {

            $listener($event);
        }
    }
}