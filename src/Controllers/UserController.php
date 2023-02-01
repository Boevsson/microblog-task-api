<?php

namespace App\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Respect\Validation\Validator;
use Slim\Http\Request;
use Slim\Http\Response;

class UserController extends Controller
{
    public function create(Request $request, Response $response)
    {
        $validator = $this->validator->validate($request, [
            'email' => Validator::email()->notBlank(),
            'password' => Validator::length(3)->notBlank()
        ]);

        if (!$validator->isValid()) {

            return $response->withJson($validator->getErrors(), 422);
        }

        $data = $request->getParsedBody();

        if (User::where(['email' => $data['email']])->first()) {

            return $response->withJson('User with this email already exists', 422);
        }

        $user = User::create([
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT)
        ]);

        return $response->withJson($user);
    }
}