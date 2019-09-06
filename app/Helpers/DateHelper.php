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
}