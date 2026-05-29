<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../src/Infrastructure/Support/helpers.php';

use Dotenv\Dotenv;
use Src\Core\Container\Container;
use Src\Core\Exceptions\Handler;
use Src\Infrastructure\Events\EventDispatcher;
use Modules\User\Application\Events\UserCreatedEvent;
use Modules\User\Application\Listeners\SendWelcomeEmailListener;
use Src\Providers\AppServiceProvider;
use Src\Providers\EventServiceProvider;
use Src\Support\Config;
use Src\Core\Providers\ProviderRepository;


$dotenv = Dotenv::createImmutable(
    __DIR__ . '/../'
);

$dotenv->load();

Config::load();

$container =
    new Container();

// $container->test();

$providers =
    config('providers.providers');

$repository =
    new ProviderRepository(
        $container
    );

$repository->load(
    $providers
);

/*
|--------------------------------------------------------------------------
| Register Providers
|--------------------------------------------------------------------------
*/

$providers = [

    AppServiceProvider::class,

    EventServiceProvider::class

];

foreach ($providers as $provider) {

    (new $provider(
        $container
    ))->register();
}


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