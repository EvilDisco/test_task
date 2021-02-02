<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Image;
use App\Entity\File;

final class CreateImageAction extends AbstractController
{
    public function __invoke(Request $request, SluggerInterface $slugger)
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $imageDir = $this->getParameter('image_directory');

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

        try {
            $uploadedFile->move(
                $imageDir,
                $newFilename
            );
        } catch (FileException $e) {
            // TODO: handle exception properly
        }

        $imageFile = new File();
        $imageFile->setName($newFilename);
        $imageFile->setPath($imageDir.'/'.$newFilename);

        $image = new Image();
        $image->setName($request->request->get('name'));
        $image->setDescription($request->request->get('description'));

        $image->setCategory($request->request->get('category'));

        $image->setFile($imageFile);

        return $image;
    }
}