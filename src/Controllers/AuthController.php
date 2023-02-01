<?php

namespace App\Controllers;

use App\Models\User;
use Respect\Validation\Validator;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthController extends Controller
{
    public function me(Request $request, Response $response)
    {
        return $request->getAttribute('current_user');
    }

    public function login(Request $request, Response $response)
    {
        $validator = $this->validator->validate($request, [
            'email' => Validator::email()->notBlank(),
            'password' => Validator::length(3)->notBlank()
        ]);

        if (!$validator->isValid()) {

            return $response->withJson($validator->getErrors(), 422);
        }

        $data = $request->getParsedBody();

        if (!($user = User::where(['email' => $data['email']])->first())) {

            return $response->withJson('User not found.', 404);
        }

        if (!password_verify($data['password'], $user->password)) {

            return $response->withJson('Unauthenticated.', 401);
        }

        $token = $user->createToken();

        // return token
        return $response->withJson([
            'access_token' => $token,
            'user' => $user
        ], 200);
    }
}