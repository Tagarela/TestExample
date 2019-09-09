<?php
declare(strict_types=1);

namespace App\Helpers;

/**
 * Class DateHelper
 *
 * @package App\Helpers
 */
class DateHelper
{
    /**
     * Get current dateTime
     *
     * @return \DateTime
     */
    public static function getCurrentDateTime(): \DateTime
    {
        $currentDate = new \DateTime();
        $currentDate->setTimezone(new \DateTimeZone('UTC'));

        return $currentDate;
    }

    /**
     * Get number of days in the month by date
     *
     * @param \DateTime $date
     *
     * @return int
     */
    public static function getNumberOfMonthDays(\DateTime $date)
    {
        $month = intval($date->format('m'));
        $year = intval($date->format('Y'));

        return cal_days_in_month(CAL_GREGORIAN,$month,$year);
    }

    /**
     * Get number of current month days
     *
     * @return int
     */
    public static function getNumberOfCurrentMonthDays()
    {
        $currentDate = self::getCurrentDateTime();

        return self::getNumberOfMonthDays($currentDate);
    }

}