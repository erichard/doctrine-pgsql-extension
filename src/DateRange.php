<?php

namespace PostgreSQLDoctrineType;

class DateRange implements \JsonSerializable
{
    const REGEX = '(\[|\()"?(\d{4}-\d{2}-\d{2}( \d{2}:\d{2}:\d{2})?)?"?,"?(\d{4}-\d{2}-\d{2}( \d{2}:\d{2}:\d{2})?)?"?(\]|\))';

    /**
     * @var \DateTimeInterface
     */
    private $startDate;

    /**
     * @var \DateTimeInterface
     */
    private $endDate;

    /**
     * @var string
     */
    private $format;

    /**
     * DateRange constructor.
     *
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     */
    public function __construct(\DateTime $startDate = null, \DateTime $endDate = null, $format = 'Y-m-d')
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->format = $format;
    }

    /**
     * @param $string
     *
     * @return DateRange
     */
    public static function fromString($string)
    {
        if (!preg_match('/^'.self::REGEX.'$/', $string, $matches)) {
            throw new \InvalidArgumentException('The given string does not match the range format: '.self::REGEX);
        }

        $lowerLimit = $matches[1];
        $upperLimit = $matches[6];

        $startDate = empty($matches[2]) ? null : new \DateTime($matches[2]);
        $endDate = empty($matches[4]) ? null : new \DateTime($matches[4]);

        if (null !== $endDate && ')' === $upperLimit) {
            $endDate = $endDate->modify('+1 day');
        }

        $format = empty($matches[3]) && empty($matches[5]) ? 'Y-m-d' : 'Y-m-d H:i:s';

        return new self($startDate, $endDate, $format);
    }

    /**
     * @return \DateTimeInterface
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    public function contains(\DateTime $date)
    {
        return $this->startDate <= $date &&
            $this->endDate > $date
        ;
    }

    /**
     * @param \DateInterval $dateInterval
     *
     * @return \DatePeriod
     */
    public function getDatePeriod(\DateInterval $dateInterval)
    {
        return new \DatePeriod($this->startDate, $dateInterval, $this->endDate);
    }

    /**
     * @param DateRange $dateRange
     *
     * @return string
     */
    public static function toString(DateRange $dateRange)
    {
        $string = '['.$dateRange->getStartDate()->format($dateRange->getFormat()).',';

        $string .= null === $dateRange->getEndDate() ?
            ')':
            $dateRange->getEndDate()->format($dateRange->getFormat()).']'
        ;

        return $string;
    }

    /**
     * @param DateRange $dateRange
     * @return bool
     */
    public function isOverlapping(DateRange $dateRange)
    {
        if ($this->startDate < $dateRange->getStartDate()) {
            return null === $this->endDate || $this->endDate > $dateRange->getStartDate();
        } elseif ($this->startDate > $dateRange->getStartDate()) {
            return $this->startDate < $dateRange->getEndDate();
        } else {
            return true;
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return self::toString($this);
    }

    public function jsonSerialize()
    {
        return [
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'format' => $this->format,
        ];
    }
}
