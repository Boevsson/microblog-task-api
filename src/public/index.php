<?php

use App\Models\User;
use Slim\App;
use Slim\Middleware\TokenAuthentication;

$rootPath = realpath(__DIR__ . '/../..');

include_once($rootPath . '/vendor/autoload.php');

$settings = require $rootPath . '/config/settings.php';

$authenticator = function($request, TokenAuthentication $tokenAuth){

    # Search for token on header, parameter, cookie or attribute
    $token = $tokenAuth->findToken($request);

    return User::where('token', $token)->first();
};

$app = new App($settings);

$app->add(new TokenAuthentication([
    'path' => '/api',
    'authenticator' => $authenticator
]));

$dependencies = require $rootPath . '/config/dependencies.php';
$dependencies($app);

$routes = require '../routes.php';
$routes($app);

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->run();