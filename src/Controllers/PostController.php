<?php

namespace App\Controllers;

use App\Models\Post;
use App\Service\ImageService;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator;

class PostController extends Controller
{
    public function getAll(Request $request, Response $response, array $args)
    {
        $posts = $this->container->get(Post::class)::all();

        return $response->withJson($posts);
    }

    public function getOne(Request $request, Response $response, array $args)
    {
        $post = Post::findOrFail($args['id']);

        return $response->withJson($post);
    }

    public function create(Request $request, Response $response, array $args)
    {
        $validator = $this->container->get('validator')->validate($request, [
            'title' => Validator::length(3, 100)->notBlank(),
            'content' => Validator::notBlank()
        ]);

        if (!$validator->isValid()) {

            return $response->withJson($validator->getErrors(), 422);
        }

        if (!$request->getUploadedFiles()) {

            return $response->withJson(['file' => 'File is required.'], 422);
        }

        $uploadedFile = $request->getUploadedFiles()['file'];
        $fileInfo = getimagesize($uploadedFile->file);

        if ($fileInfo['mime'] != 'image/jpeg' && $fileInfo['mime'] != 'image/png') {

            return $response->withJson(['error' => 'Invalid image format'], 422);
        }

        $data = $request->getParsedBody();

        $imageService = $this->container->get(ImageService::class);
        $filename = $imageService->saveUploadedFile($uploadedFile);

        $post = Post::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'image_file_name' => $filename,
        ]);

        return $response->withJson($post);
    }

    public function update(Request $request, Response $response, array $args)
    {
        $validator = $this->validator->validate($request, [
            'title' => Validator::length(3, 100)->notBlank(),
            'content' => Validator::notBlank()
        ]);

        if (!$validator->isValid()) {

            return $response->withJson($validator->getErrors(), 422);
        }

        $post = Post::findOrFail($args['id']);

        $data = $request->getParsedBody();
        $postData = [
            'title' => $data['title'],
            'content' => $data['content']
        ];

        // handle image only if uploaded
        if ($request->getUploadedFiles()) {

            $imageService = $this->container->get(ImageService::class);
            $filename = $imageService->saveUploadedFile();
            $postData['image_file_name'] = $filename;
        }

        $post->fill($postData);
        $post->save();

        return $response->withJson($post);
    }

    public function delete(Request $request, Response $response, array $args)
    {
        Post::findOrFail($args['id'])->delete();

        return $response->withJson(null, 204);
    }
}