<?php

namespace PostgreSQLDoctrineType\DBAL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;

class TsRangeType extends DateRangeType
{
    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'tsrange';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'daterange';
    }
}
