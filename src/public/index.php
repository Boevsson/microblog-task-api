<?php

use Slim\App;

$rootPath = realpath(__DIR__ . '/../..');

include_once($rootPath . '/vendor/autoload.php');

$settings = require $rootPath . '/config/settings.php';

$app = new App($settings);

require $rootPath . '/config/dependencies.php';

$routes = require '../routes.php';
$routes($app);

$app->run();