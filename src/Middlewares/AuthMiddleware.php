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

            $user = User::where('token', $token)->first();

            if (!$user){
                return $response->withStatus(401);
            }

            $request = $request->withAttribute('current_user', $user);
        }

        return $next($request, $response);
    }
}