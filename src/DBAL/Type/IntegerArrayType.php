<?php

namespace PostgreSQLDoctrineType\DBAL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class IntegerArrayType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $size = $fieldDeclaration['array_size'] ?? '';

        return "integer[$size]";
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
        if ('{}' === $value) {
            return [];
        }

        $array = array_map('intval', explode(',', trim($value, '{}')));
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
        return 'integer_array';
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
