<?php

namespace Tests\Feature;

use Slim\App;
use Slim\Http\Request;
use Slim\Http\UploadedFile;
use Tests\TestCase;

class LoginTest extends TestCase
{
    protected App $app;
    protected bool $refreshDatabase = true;

    public function testLogin()
    {
        $response = $this->makeRequest('POST', '/login', [
            'email' => 'test@email.com',
            'password' => 'rocknroll'
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode((string)$response->getBody(), true);

        $this->assertArrayHasKey('user', $responseData);
        $this->assertArrayHasKey('access_token', $responseData);
        $this->assertArrayNotHasKey('password', $responseData);

        $user = $responseData['user'];

        $this->assertSame(1, $user['id']);
        $this->assertSame('test@email.com', $user['email']);
    }

    public function testLoginValidation()
    {
        $response = $this->makeRequest('POST', '/login', []);

        $responseData = json_decode((string)$response->getBody(), true);

        $this->assertEquals(422, $response->getStatusCode());
    }
}