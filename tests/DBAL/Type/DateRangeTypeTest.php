<?php

namespace PostgreSQLDoctrineType\DBAL\Type;

use PHPUnit\Framework\TestCase;
use PostgreSQLDoctrineType\DateRange;

class DateRangeTypeTest extends TestCase
{
    /**
     * @dataProvider validStringProvider
     */
    public function testCreationFromString($string, $expectedRange)
    {
        $range = DateRange::fromString($string);

        $this->assertEquals($expectedRange->getStartDate(), $range->getStartDate());
        $this->assertEquals($expectedRange->getEndDate(), $range->getEndDate());
        $this->assertEquals($expectedRange->getFormat(), $range->getFormat());
    }

    /**
     * @dataProvider overlapProvider
     */
    public function testOverlapping($a, $b, $expected)
    {
        $rangeA = DateRange::fromString($a);
        $rangeB = DateRange::fromString($b);

        $this->assertEquals($rangeA->isOverlapping($rangeB), $expected);
    }

    public function validStringProvider()
    {
        return [
            ['["2017-08-16 16:56:00","2017-08-16 17:56:00"]', new DateRange(new \DateTime('2017-08-16 16:56:00'), new \DateTime('2017-08-16 17:56:00'), 'Y-m-d H:i:s')],
            ['[,"2017-08-16 17:56:00"]', new DateRange(null, new \DateTime('2017-08-16 17:56:00'), 'Y-m-d H:i:s')],
            ['["2017-08-16 16:56:00",]', new DateRange(new \DateTime('2017-08-16 16:56:00'), null, 'Y-m-d H:i:s')],
            ['[2017-08-16,2017-09-30)', new DateRange(new \DateTime('2017-08-16'), new \DateTime('2017-10-01'), 'Y-m-d')],
            ['[2017-08-16,)', new DateRange(new \DateTime('2017-08-16'), null, 'Y-m-d')],
            ['[,2017-09-30)', new DateRange(null, new \DateTime('2017-10-01'), 'Y-m-d')],
        ];
    }

    public function overlapProvider()
    {
        return [
            ['["2017-08-16 16:56:00","2017-08-16 17:56:00"]', '["2017-08-16 16:58:00","2017-08-16 17:56:00"]', true],
            ['["2017-08-16 13:56:00","2017-08-16 15:56:00"]', '["2017-08-16 16:56:00","2017-08-16 17:56:00"]', false],
            ['["2017-08-16 12:56:00","2017-08-16 20:56:00"]', '["2017-08-16 16:56:00","2017-08-16 17:56:00"]', true],
            ['["2017-08-16 13:56:00","2017-08-16 15:56:00"]', '["2017-08-16 12:56:00","2017-08-16 14:56:00"]', true],
            ['[2017-01-01,)', '["2017-08-16 12:56:00","2017-08-16 14:56:00"]', true],
        ];
    }
}
