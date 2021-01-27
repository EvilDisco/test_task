<?php

namespace App\DBAL;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class EnumImageCategoryType extends Type
{
    const ENUM_IMAGE_CATEGORY = 'enum_image_category';
    const CATEGORY_NEW = 'new';
    const CATEGORY_POPULAR = 'popular';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('new', 'popular')";
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, array(self::CATEGORY_NEW, self::CATEGORY_POPULAR))) {
            throw new \InvalidArgumentException("Invalid category");
        }
        return $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }

    public function getName()
    {
        return self::ENUM_IMAGE_CATEGORY;
    }
}