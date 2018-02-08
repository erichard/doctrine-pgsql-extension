<?php

namespace PostgreSQLDoctrineType\DBAL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class VarcharArrayType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $size = $fieldDeclaration['array_size'] ?? '';

        return "varchar[$size]";
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

        if ('{}' === $value) {
            return [];
        }

        $array = explode(',', strtr($value, ['{' => '', '}' => '']));
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
        return 'varchar_array';
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
