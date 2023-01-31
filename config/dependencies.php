<?php

use Illuminate\Database\Capsule\Manager;
use App\Handler\Handler;
use Slim\Container;

$container = $app->getContainer();

$container['errorHandler'] = function (Container $container) {
    return new Handler();
};

$container['validator'] = function () {
    return new Awurth\SlimValidation\Validator();
};

$container['upload_directory'] = __DIR__ . '/uploads';

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