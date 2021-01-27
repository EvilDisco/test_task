<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Image;
use App\Entity\File;
use App\DBAL\EnumImageCategoryType;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $file = new File();
            $file->setName('file '.$i);
            $file->setPath('some_path_to_image_file.png');
            $manager->persist($file);

            $img = new Image();
            $img->setName('image '.$i);
            $img->setDescription('there goes description of image '.$i);

            $imgCategories = [ EnumImageCategoryType::CATEGORY_NEW, EnumImageCategoryType::CATEGORY_POPULAR ];
            $img->setCategory($imgCategories[ array_rand($imgCategories) ]);

            $img->setFile($file);

            $manager->persist($img);
        }

        $manager->flush();
    }
}
