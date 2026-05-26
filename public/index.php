<?php

require_once __DIR__ . '/../bootstrap/app.php';

use Src\Presentation\Http\Request;
use Src\Presentation\Routing\Router;
use Src\Presentation\Http\Response;
use Src\Presentation\Middleware\AuthMiddleware;

use Modules\User\Presentation\Controllers\AuthController;
use Modules\User\Presentation\Controllers\UserController;

$request = new Request();

$router = new Router();

$authController =
    new AuthController();

$userController =
    new UserController();

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

$router->get(

    '/api/users',

    [$userController, 'index'],

    [
        AuthMiddleware::class
    ]

);


$router->post(

    '/api/users',

    [$userController, 'create'],

    [
        AuthMiddleware::class
    ]

);

$router->dispatch(
    $request->method(),
    $request->uri()
);