<?php

require_once __DIR__ . '/../bootstrap/app.php';

use Src\Presentation\Http\Request;
use Src\Presentation\Routing\Router;
use Src\Presentation\Http\Response;
use Src\Presentation\Middleware\AuthMiddleware;

use Modules\User\Presentation\Controllers\AuthController;
use Modules\User\Presentation\Controllers\UserController;
use Src\Presentation\Middleware\RoleMiddleware;
use Src\Presentation\Middleware\PermissionMiddleware;

// throw new Exception('Test Error');

$request = new Request();

$router = new Router();

// $authController =
//     new AuthController();

// $userController =
//     new UserController();

$container =
    $GLOBALS['container'];

$authController =
    $container->get(
        AuthController::class
    );

$userController =
    $container->get(
        UserController::class
    );

$router->post(
    '/api/auth/login',
    [$authController, 'login']
);

$router->get(

    '/api/me',

    function () {

        Response::json([

            'user' => $_SERVER['user']

        ]);

    },

    [
        AuthMiddleware::class
    ]

);

// $router->get(

//     '/api/users',

//     [$userController, 'index'],

//     [
//         AuthMiddleware::class
//     ]

// );

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





$router->dispatch(
    $request->method(),
    $request->uri()
);

// echo $_ENV['DB_DATABASE'];
// die();