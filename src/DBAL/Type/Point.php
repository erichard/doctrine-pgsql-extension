<?php

namespace PostgreSQLDoctrineType\DBAL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class Point extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'point';
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value || '' === $value) {
            return null;
        }

        return sprintf("(%f,%f)", $value['x'], $value['y']);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        $value = trim($value, '()');
        list($x, $y) = explode(',', $value);

        $point = [
            'x' => $x,
            'y' => $y,
        ];

        return $point;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'point';
    }
}
