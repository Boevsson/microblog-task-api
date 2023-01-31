<?php

namespace App\Service;

class ImageService extends Service
{
    public function saveUploadedFile()
    {
        $uploadDirectory = $this->container->get('upload_directory');

        $storage = new \Upload\Storage\FileSystem($uploadDirectory);
        $file = new \Upload\File('file', $storage);

        $file->addValidations(array(
            // Ensure file is of type "image/png"
            new \Upload\Validation\Mimetype(['image/png', 'image/jpg', 'image/jpeg', 'image/gif']),
            new \Upload\Validation\Size('5M')
        ));

        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $file->getExtension());

        $file->upload($filename);

        return $filename;
    }
}