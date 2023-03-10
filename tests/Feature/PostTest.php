<?php

namespace Tests\Feature;

use App\Models\Post;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\UploadedFile;
use Tests\TestCase;

class PostTest extends TestCase
{
    protected App $app;
    protected bool $refreshDatabase = true;

    public function testGetAllPosts()
    {
//        $mock = Mockery::mock(Post::class);
//
//        $mock->shouldReceive('all')
//            ->once()
//            ->andReturn([]);
//
//        $container = $this->app->getContainer();
//        $container[Post::class] = $mock;

        $response = $this->makeRequest('GET', '/posts');

        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode((string)$response->getBody(), true);
        $firstPost = $responseData[0];

        $this->assertArrayHasKey('id', $firstPost);
        $this->assertArrayHasKey('title', $firstPost);
        $this->assertArrayHasKey('content', $firstPost);
        $this->assertArrayHasKey('image_file_name', $firstPost);
        $this->assertArrayHasKey('created_at', $firstPost);
        $this->assertArrayHasKey('updated_at', $firstPost);

        $this->assertSame(1, $firstPost['id']);
        $this->assertSame('test', $firstPost['title']);
        $this->assertSame('content', $firstPost['content']);
        $this->assertSame('1cae0d4fd155afca.jpg', $firstPost['image_file_name']);
        $this->assertSame('2023-02-01 03:09:36', $firstPost['created_at']);
        $this->assertSame('2023-02-01 03:09:36', $firstPost['updated_at']);
    }

    public function testGetOnePost()
    {
        $response = $this->makeRequest('GET', '/posts/1');

        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode((string)$response->getBody(), true);

        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('title', $responseData);
        $this->assertArrayHasKey('content', $responseData);
        $this->assertArrayHasKey('image_file_name', $responseData);
        $this->assertArrayHasKey('created_at', $responseData);
        $this->assertArrayHasKey('updated_at', $responseData);

        $this->assertSame(1, $responseData['id']);
        $this->assertSame('test', $responseData['title']);
        $this->assertSame('content', $responseData['content']);
        $this->assertSame('1cae0d4fd155afca.jpg', $responseData['image_file_name']);
        $this->assertSame('2023-02-01 03:09:36', $responseData['created_at']);
        $this->assertSame('2023-02-01 03:09:36', $responseData['updated_at']);
    }

    public function testCreatePostValidation()
    {
        $response = $this->makeRequest('POST', '/posts', [
        ]);

        $this->assertEquals(422, $response->getStatusCode());
        $responseData = json_decode((string)$response->getBody(), true);

        $this->assertSame([
            'title' => [
                'length' => 'null must have a length between 3 and 100',
                'notBlank' => 'null must not be blank',
            ],
            'content' => [
                'notBlank' => 'null must not be blank'
            ]
        ], $responseData);
    }

    public function testCreatePost()
    {
        $local_file = dirname(__DIR__) . '/fixtures/avatar.png';
        $temp_file = tempnam(sys_get_temp_dir(), 'prefix');

        if (!copy($local_file, $temp_file)) {
            // Return an error
            return "Failed to create temporary file.";
        }

        $file       = new UploadedFile(
            $temp_file,
            'avatar.png',
            'image/png',
            49518
        );
        $files      = ['file' => $file];

        $response = $this->makeRequest('POST', '/posts', [
            'title' => 'Test post',
            'content' => 'Content test'
        ], $files);

        $responseData = json_decode((string)$response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('title', $responseData);
        $this->assertArrayHasKey('content', $responseData);
        $this->assertArrayHasKey('image_file_name', $responseData);
        $this->assertArrayHasKey('created_at', $responseData);
        $this->assertArrayHasKey('updated_at', $responseData);

        $this->assertSame(3, $responseData['id']);
        $this->assertSame('Test post', $responseData['title']);
        $this->assertSame('Content test', $responseData['content']);
    }

    public function testUpdatePost()
    {
        $response = $this->makeRequest('POST', '/posts/1', [
            'title' => 'Test post',
            'content' => 'Content test'
        ]);

        $responseData = json_decode((string)$response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('title', $responseData);
        $this->assertArrayHasKey('content', $responseData);
        $this->assertArrayHasKey('image_file_name', $responseData);
        $this->assertArrayHasKey('created_at', $responseData);
        $this->assertArrayHasKey('updated_at', $responseData);

        $this->assertSame(1, $responseData['id']);
        $this->assertSame('Test post', $responseData['title']);
        $this->assertSame('Content test', $responseData['content']);
    }

    public function testDeletePost()
    {
        $response = $this->makeRequest('DELETE', '/posts/1');

        $responseData = json_decode((string)$response->getBody(), true);

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertSame(null, $responseData);

        $this->assertSame(null, Post::find(1));
    }
}