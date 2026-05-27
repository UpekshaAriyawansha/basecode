<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../src/Infrastructure/Support/helpers.php';

use Dotenv\Dotenv;

use Src\Core\Container\Container;


use Src\Core\Exceptions\Handler;

use Src\Core\Events\EventDispatcher;

use Modules\User\Application\Events\UserCreatedEvent;

use Modules\User\Application\Listeners\SendWelcomeEmailListener;

$dotenv = Dotenv::createImmutable(
    __DIR__ . '/../'
);

$dotenv->load();


$container =
    new Container();


$GLOBALS['container'] =
    $container;


Handler::register();


$events =
    new EventDispatcher();

$events->listen(

    UserCreatedEvent::class,

    [
        new SendWelcomeEmailListener(),
        'handle'
    ]

);

$GLOBALS['events'] =
    $events;