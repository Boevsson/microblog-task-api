<?php

namespace App\Middlewares;

use App\Models\User;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthMiddleware
{
    public function __invoke(Request $request, Response $response, $next) {
        $header = $request->getHeader('Authorization');

        if ($header) {

            $token = explode(' ', $header[0])[1];

            if (!User::where('token', $token)->first()){
                return $response->withStatus(401);
            }
        }

        return $next($request, $response);
    }
}