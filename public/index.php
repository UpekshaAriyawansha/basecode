<?php

require_once __DIR__ . '/../bootstrap/app.php';

use Src\Presentation\Http\Request;
use Src\Presentation\Routing\Router;
use Src\Presentation\Http\Response;

use Src\Presentation\Middleware\AuthMiddleware;
use Src\Presentation\Middleware\RoleMiddleware;
use Src\Presentation\Middleware\PermissionMiddleware;

use Src\Infrastructure\Cache\CacheManager;

use Modules\User\Presentation\Controllers\AuthController;
use Modules\User\Presentation\Controllers\UserController;
use Modules\User\Presentation\Controllers\UploadController;

/*
|--------------------------------------------------------------------------
| Application
|--------------------------------------------------------------------------
*/

$request = new Request();

$router = new Router();

/*
|--------------------------------------------------------------------------
| Container
|--------------------------------------------------------------------------
*/

$container =
    $GLOBALS['container'];

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/

$authController =
    $container->make(
        AuthController::class
    );

$userController =
    $container->make(
        UserController::class
    );

$uploadController =
    $container->make(
        UploadController::class
    );

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

$router->post(

    '/api/auth/login',

    [$authController, 'login']

);

/*
|--------------------------------------------------------------------------
| Current User
|--------------------------------------------------------------------------
*/

$router->get(

    '/api/me',

    function () {

        Response::json([

            'user' =>

                $_SERVER['user']

        ]);

    },

    [

        AuthMiddleware::class

    ]

);

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

$router->get(

    '/api/users',

    [$userController, 'index'],

    [

        AuthMiddleware::class,

        [

            PermissionMiddleware::class,

            ['users.view']

        ]

    ]

);

$router->post(

    '/api/users',

    [$userController, 'create'],

    [

        AuthMiddleware::class

    ]

);

$router->get(

    '/api/users/{id}',

    [$userController, 'show'],

    [

        AuthMiddleware::class

    ]

);

$router->put(

    '/api/users/{id}',

    [$userController, 'update'],

    [

        AuthMiddleware::class

    ]

);

$router->delete(

    '/api/users/{id}',

    [$userController, 'delete'],

    [

        AuthMiddleware::class

    ]

);

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

$router->get(

    '/api/admin',

    function () {

        Response::json([

            'message' =>

                'Welcome Admin'

        ]);

    },

    [

        AuthMiddleware::class,

        [

            RoleMiddleware::class,

            ['admin']

        ]

    ]

);

/*
|--------------------------------------------------------------------------
| Upload Routes
|--------------------------------------------------------------------------
*/

$router->post(

    '/api/upload',

    [$uploadController, 'upload'],

    [

        AuthMiddleware::class

    ]

);

/*
|--------------------------------------------------------------------------
| Cache Test
|--------------------------------------------------------------------------
*/

$router->get(

    '/api/cache-test',

    function () {

        CacheManager::driver()->put(

            'name',

            'Upeksha',

            5

        );

        $value =
            CacheManager::driver()->get(
                'name'
            );

        Response::json([

            'cache' => $value

        ]);
    }

);

/*
|--------------------------------------------------------------------------
| Dispatch Request
|--------------------------------------------------------------------------
*/

$router->dispatch(

    $request->method(),

    $request->uri()

);