<?php
declare(strict_types=1);

namespace App\Helpers;

use Carbon\Carbon;

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
     * @return Carbon
     */
    public static function getCurrentDateTime(): Carbon
    {
        return Carbon::now(new \DateTimeZone('UTC'));
    }

    /**
     * Get number of days in the month by date
     *
     * @param Carbon $date
     *
     * @return int
     */
    public static function getNumberOfMonthDays(Carbon $date): int
    {
        $month = intval($date->format('m'));
        $year = intval($date->format('Y'));

        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    /**
     * Get number of current month days
     *
     * @return int
     */
    public static function getNumberOfCurrentMonthDays(): int
    {
        $currentDate = self::getCurrentDateTime();

        return self::getNumberOfMonthDays($currentDate);
    }

}