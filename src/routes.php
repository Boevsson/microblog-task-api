<?php
declare(strict_types=1);

use Slim\App;

return function (App $app) {
    $container = $app->getContainer();
    $authMiddleware = $container->get('authMiddleware');

    $app->get('/posts', \App\Controllers\PostController::class . ':getAll');
    $app->get('/posts/{id}', \App\Controllers\PostController::class . ':getOne');
    $app->post('/posts', \App\Controllers\PostController::class . ':create')
        ->add($authMiddleware);
    $app->post('/posts/{id}', \App\Controllers\PostController::class . ':update')
        ->add($authMiddleware);
    $app->delete('/posts/{id}', \App\Controllers\PostController::class . ':delete')
        ->add($authMiddleware);

    $app->post('/users', \App\Controllers\UserController::class . ':create');
    $app->post('/login', \App\Controllers\AuthController::class . ':login');
    $app->get('/me', \App\Controllers\AuthController::class . ':me')
        ->add($authMiddleware);
};
