<?php

namespace PostgreSQLDoctrineType\DBAL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class BooleanArrayType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $size = $fieldDeclaration['array_size'] ?? '';

        return "bool[$size]";
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return (null === $value) ? null : '{'.implode(',', $value).'}';
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        $array = array_map(function ($item) {
            return 't' === $item;
        }, explode(',', trim($value, '{}')));

        $array = array_map(function ($item) {
            return 'NULL' === $item ? null : $item;
        }, $array);

        return $array;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'boolean_array';
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
