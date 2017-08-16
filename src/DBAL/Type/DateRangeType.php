<?php

namespace PostgreSQLDoctrineType\DBAL\Type;

use PostgreSQLDoctrineType\DateRange;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\conversionFailedFormat;

class DateRangeType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'daterange';
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return (null === $value) ? null : DateRange::toString($value);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null !== $value) {
            try {
                $value = DateRange::fromString($value);
            } catch (\InvalidArgumentException $exception) {
                throw ConversionException::conversionFailedFormat(
                    $value,
                    $this->getName(),
                    DateRange::REGEX,
                    $exception
                );
            }
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'daterange';
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return false;
    }
}
