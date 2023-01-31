<?php

namespace App\Handler;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class Handler
{
    public function __invoke(Request $request, Response $response, Exception $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return $response->withJson('Resource not found.', 404);
        }

        return $response->withJson($exception->getMessage(), 500);
    }
}