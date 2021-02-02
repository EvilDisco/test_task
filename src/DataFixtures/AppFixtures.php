<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\File;
use App\DBAL\EnumImageCategoryType;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail('user'.$i.'@example.com');
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'the_new_password'
            ));
            $manager->persist($user);

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

            $img->setUser($user);

            $manager->persist($img);
        }

        $manager->flush();
    }
}
