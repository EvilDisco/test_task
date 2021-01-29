<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Entity\File;

final class CreateFileAction
{
    public function __invoke(Request $request): File
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $imageFile = new File();
        $imageFile->file = $uploadedFile;

        return $imageFile;
    }
}