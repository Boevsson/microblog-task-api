<?php

namespace App\Service;

use Slim\Http\UploadedFile;

class ImageService extends Service
{
    public function saveUploadedFile(UploadedFile $uploadedFile)
    {
        $uploadDirectory = $this->container->get('uploadDirectory');

        return $this->moveUploadedFile($uploadDirectory, $uploadedFile);
    }

    private function moveUploadedFile($directory, UploadedFile $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}