<?php

namespace PostgreSQLDoctrineType\DBAL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType as BaseJsonType;

class JsonType extends BaseJsonType
{
    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'JSONB';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'jsonb';
    }
}
