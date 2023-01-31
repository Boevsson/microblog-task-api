<?php
declare(strict_types=1);

use Slim\App;

return function (App $app) {
//    $container = $app->getContainer();
//    $settings = $container->get('settings');

    $app->get('/posts', \App\Controllers\PostController::class . ':getAll');
    $app->get('/posts/{id}', \App\Controllers\PostController::class . ':getOne');
    $app->post('/posts', \App\Controllers\PostController::class . ':create');
    $app->post('/posts/{id}', \App\Controllers\PostController::class . ':update');
    $app->delete('/posts/{id}', \App\Controllers\PostController::class . ':delete');
};
