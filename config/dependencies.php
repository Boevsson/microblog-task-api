<?php

use App\Middlewares\AuthMiddleware;
use Illuminate\Database\Capsule\Manager;
use App\Handler\Handler;
use Slim\App;
use Slim\Container;

return function (App $app) {

    $container = $app->getContainer();

    $container['errorHandler'] = function (Container $container) {
        return new Handler();
    };

    $container['validator'] = function () {
        return new Awurth\SlimValidation\Validator();
    };

    $container['authMiddleware'] = function () {
        return new AuthMiddleware();
    };

    $container[\App\Models\Post::class] = function () {
        return new \App\Models\Post();
    };

    $container['uploadDirectory'] = __DIR__ . '/../src/public/uploads';

// Setup Eloquent database manager
    $capsule = new Manager;
    $capsule->addConnection($container['settings']['db']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    $container['db'] = function () use ($capsule) {
        return $capsule;
    };

    $container[\App\Service\ImageService::class] = function ($container) {
        return new \App\Service\ImageService($container);
    };
};